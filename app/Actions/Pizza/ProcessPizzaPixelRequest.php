<?php

namespace AnchorCMS\Actions\Pizza;

use AnchorCMS\Services\PizzaPixelIntakeService;
use Illuminate\Support\Facades\Log;

class ProcessPizzaPixelRequest
{
    protected $token, $pixel_id, $client_id;
    protected $dominos;

    public function __construct(PizzaPixelIntakeService $pizza_hut)
    {
        // fight me on this. You won't cuz it's amazing. And wrong. And right. And pizza.
        $this->dominos = $pizza_hut;
    }

    public function execute(array $data = [], string $ip = null) : void
    {
        if(count($data) > 0)
        {
            $this->token = $data['token'];
            $this->pixel_id = $data['pixel_id'];
            $this->client_id = $data['client_id'];
            $marcos_pizza = $this->dominos;

            // verify the token belongs to the client;
            $marcos_pizza->setClient($this->client_id);
            if($marcos_pizza->verifyToken($this->token))
            {
                $marcos_pizza->setIp($ip);
                if($marcos_pizza->initPixel($this->pixel_id))
                {
                    //parse what's being requested;
                    if(array_key_exists('tracking', $data))
                    {
                        $marcos_pizza->processTrackingRequestActivity($data['tracking']);
                    }

                    if(array_key_exists('activity', $data))
                    {
                        Log::info('activity - '. $data['activity']);
                        $marcos_pizza->processTrackingRequestCollectActivity(json_decode($data['activity'],true));
                    }
                }
            }
            else
            {
                // @todo - log this to the devs.
            }
        }
        else
        {
            // @todo - log this to the devs.
        }
    }
}
