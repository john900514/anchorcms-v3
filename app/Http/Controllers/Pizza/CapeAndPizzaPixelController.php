<?php

namespace AnchorCMS\Http\Controllers\Pizza;

use AnchorCMS\Actions\Pizza\ProcessPizzaPixelRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ixudra\Curl\Facades\Curl;
use AnchorCMS\Http\Controllers\Controller;

class CapeAndPizzaPixelController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function get_pixel(string $client_uuid, ProcessPizzaPixelRequest $action)
    {
        $results = false;
        // Get the data.
        $data = $this->request->all();

        $validated = Validator::make($data, [
            'client_id' => 'bail|required|exists:clients,id',
            'token' => 'bail|required|exists:feature_attributes,attribute_desc',
            'pixel_id' => 'bail|required'
        ]);

        if ($validated->fails())
        {
            foreach($validated->errors()->toArray() as $idx => $error_msg)
            {
                $results = $error_msg[0];;
                break;
            }

            return response($results, 401);
        }
        else
        {
            // Call the action that determines what to do
            $action->execute($data, $this->request->ip());

            // Send back the pixel lol
            $filename = 'pixel.png';
            $tempImage = tempnam(sys_get_temp_dir(), $filename);
            copy('https://amchorcms-assets.s3.amazonaws.com/pixel.png', $tempImage);

            return response()
                ->download($tempImage, $filename, ['Content-Type', 'image/png']);
        }
    }

    public function get_pixel_js(string $client_id)
    {
        $results = false;

        $data = ['client_id' => $client_id];

        $validated = Validator::make($data, [
            'client_id' => 'bail|required|exists:clients,id',
        ]);

        if ($validated->fails())
        {
            foreach($validated->errors()->toArray() as $idx => $error_msg)
            {
                $results = $error_msg[0];;
                break;
            }

            return response($results, 401);
        }
        else
        {
            // @todo - find a way to auto generate this.
            $filename = 'capeandbaypixel.js';
            $tempImage = tempnam(sys_get_temp_dir(), $filename);
            //copy('https://amchorcms-assets.s3.amazonaws.com/capeandbaypixel.js', $tempImage);
            switch(env('APP_ENV'))
            {
                case 'local':
                    $js = '/js/pizza/capeandbaypixel-local.js';
                    break;

                case 'develop':
                case 'staging':
                    $js = '/js/pizza/capeandbaypixel-dev.js';
                    break;

                default:
                    $js = '/js/pizza/capeandbaypixel.js';
            }
            copy(asset($js), $tempImage);

            return response()
                ->file($tempImage, ['Content-Type' => 'text/javascript']);
        }
    }
}
