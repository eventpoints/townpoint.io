<?php

declare(strict_types = 1);

namespace App\Factory\image;

use App\Entity\Auction\Item;
use App\Entity\Image;

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
