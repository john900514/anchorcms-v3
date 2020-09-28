<?php

namespace AnchorCMS\Aggregates\Pizza;

use AnchorCMS\StorableEvents\Pizza\NewCollectActivityLogged;
use AnchorCMS\StorableEvents\Pizza\NewPageViewActivityLogged;
use AnchorCMS\StorableEvents\Pizza\NewPixelReceived;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class PixelAggregate extends AggregateRoot
{
    protected static bool $allowConcurrency = true;

    private $pixel_id, $client_id, $ip;

    public function addClientId($client_id)
    {
        $this->client_id = $client_id;

        return $this;
    }

    public function addIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    public function addPixelId($pixel_id, $save = true)
    {
        $this->pixel_id = $pixel_id;

        if($save)
        {
            $this->recordThat(new NewPixelReceived($pixel_id, $this->client_id, $this->ip));
        }

        return $this;
    }

    public function addNewPixelActivity($activity, $payload)
    {
        // pageView - aka pageView (string)
        // activity - aka data collected (array)
        // click - aka button was clicked (array)
        // confirmation - aka conversion was made (array)
        // input - aka input field was captured (array)
        switch($activity)
        {
            case 'pageView':
                $this->recordThat(new NewPageViewActivityLogged($this->pixel_id, $payload));
                break;

            case 'activity':
                $this->recordThat(new NewCollectActivityLogged($this->pixel_id, $payload));
                break;

            case 'click':
            case 'confirmation':
            case 'input':
        }

        return $this;
    }

}
