<?php

namespace AnchorCMS\StorableEvents\Pizza;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class NewPixelReceived extends ShouldBeStored
{
    private $pixelId, $clientId, $ip;

    public function __construct(string $pixelId, string $clientId, string $ip)
    {
        $this->ip = $ip;
        $this->pixelId = $pixelId;
        $this->clientId = $clientId;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function getClientId()
    {
        return $this->clientId;
    }

    public function getPixelId()
    {
        return $this->pixelId;
    }
}
