<?php

namespace AnchorCMS\Services;

use AnchorCMS\Aggregates\Pizza\PixelAggregate;
use AnchorCMS\Clients;
use AnchorCMS\Pixels;

class PizzaPixelIntakeService
{
    protected $clients_model, $pixel_model;
    private $token_verified = false;
    private $ip, $aggregate;

    public function __construct(Clients $clients, Pixels $pixels)
    {
        $this->pixel_model = $pixels;
        $this->clients_model = $clients;
    }

    public function setClient($client_id) : void
    {
        $client = $this->clients_model->find($client_id);

        if((!is_null($client)))
        {
            $this->clients_model = $client;
        }
    }

    public function setIp($ip) : void
    {
        $this->ip = $ip;
    }

    public function ip_address()
    {
        return $this->ip;
    }

    public function verifyToken($token) : bool
    {
        $results = false;

        if(!is_null($this->clients_model->id))
        {
            $pizza_feature = $this->clients_model->features()
                ->whereName('Tracking Pixel')
                ->whereActive(1)
                ->first();

            if(!is_null($pizza_feature))
            {
                $token_record = $pizza_feature->feature_attributes()
                    ->whereAttribute('PIZZA TOKEN')
                    ->whereAttributeDesc($token)
                    ->whereActive(1)
                    ->first();

                if(!is_null($token_record))
                {
                    $this->token_verified = true;
                    $results = true;
                }
                else
                {
                    // @todo - log a dev error here, someone shouldn't be here.
                }
            }
            else
            {
                // @todo - log a dev error here, someone shouldn't be here.
            }
        }

        return $results;
    }

    public function token_verified()
    {
        return $this->token_verified;
    }

    public function initPixel($pixel_id) : bool
    {
        $results = false;

        if($this->token_verified())
        {
            $pixel = $this->pixel_model->wherePixelId($pixel_id)
                ->first();

            $this->aggregate = PixelAggregate::retrieve($pixel_id)
                ->addClientId($this->clients_model->id)
                ->addIp($this->ip)
                ->addPixelId($pixel_id, is_null($pixel))
                ->persist();

            $results = true;
        }

        return $results;
    }

    public function getPixel()
    {
        return $this->pixel_model;
    }

    public function processTrackingRequestActivity(string $payload): void
    {
        if($this->token_verified())
        {
            $aggy = $this->aggregate;
            // tracking - aka pageView (string)

            $aggy = $aggy->addNewPixelActivity('pageView', $payload);
            $aggy->persist();
        }
    }

    public function processTrackingRequestCollectActivity(array $payload)
    {
        if($this->token_verified())
        {
            $aggy = $this->aggregate;
            // tracking - aka pageView (string)

            $aggy = $aggy->addNewPixelActivity('activity', $payload);
            $aggy->persist();
        }
    }
}
