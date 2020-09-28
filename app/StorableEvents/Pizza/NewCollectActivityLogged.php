<?php

namespace AnchorCMS\StorableEvents\Pizza;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class NewCollectActivityLogged extends ShouldBeStored
{
    private $pixelId, $payload;

    public function __construct(string $pixelId, array $payload)
    {
        $this->pixelId = $pixelId;
        $this->payload = $payload;
    }

    public function getPixelId()
    {
        return $this->pixelId;
    }

    public function getPayload()
    {
        return $this->payload;
    }
}
