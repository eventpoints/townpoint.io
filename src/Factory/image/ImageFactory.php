<?php

declare(strict_types = 1);

namespace App\Factory\image;

use App\Entity\Image;
use App\Entity\Auction\Item;

class ImageFactory
{
    public function create(string $content, null|Item $item = null): Image
    {
        $image = new Image();
        $image->setContent($content);
        $image->setItem($item);

        return $image;
    }
}
