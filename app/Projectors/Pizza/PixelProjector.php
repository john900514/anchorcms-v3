<?php

namespace AnchorCMS\Projectors\Pizza;

use AnchorCMS\PixelActivity;
use AnchorCMS\Pixels;
use AnchorCMS\StorableEvents\Pizza\NewCollectActivityLogged;
use AnchorCMS\StorableEvents\Pizza\NewPageViewActivityLogged;
use AnchorCMS\StorableEvents\Pizza\NewPixelReceived;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class PixelProjector extends Projector
{
    public function onNewPixelReceived(NewPixelReceived $event)
    {

        $payload = [
            'client_id' => $event->getClientId(),
            'pixel_id' => $event->getPixelId(),
            'ip' => $event->getIp()
        ];

        Pixels::create($payload);
    }

    public function onNewPageViewActivityLogged(NewPageViewActivityLogged $event)
    {
        // cut the record with the pixel id and the page name
        $payload = [
            'pixel_id' => $event->getPixelId(),
            'activity' => 'pageView',
            'value' => $event->getPage(),
            'misc' => []
        ];

        PixelActivity::create($payload);
    }

    public function onNewCollectActivityLogged(NewCollectActivityLogged $event)
    {
        $payload = [
            'pixel_id' => $event->getPixelId(),
            'activity' => 'collect',
            'value' => $event->getPayload()['activity'],
            'misc' => $event->getPayload()['data']
        ];

        PixelActivity::create($payload);
    }

}
