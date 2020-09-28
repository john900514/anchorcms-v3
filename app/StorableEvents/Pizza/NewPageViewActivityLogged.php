<?php

namespace AnchorCMS\StorableEvents\Pizza;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class NewPageViewActivityLogged extends ShouldBeStored
{
    private $pixelId, $page;

    public function __construct(string $pixelId, string $page)
    {
        $this->page = $page;
        $this->pixelId = $pixelId;
    }

    public function getPixelId()
    {
        return $this->pixelId;
    }

    public function getPage()
    {
        return $this->page;
    }
}
