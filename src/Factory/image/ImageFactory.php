<?php

namespace App\Factory\image;

use App\Entity\Image;
use App\Entity\Market\Item;

class ImageFactory
{
    public function create(string $content, null|Item $item = null) : Image
    {
        $image = new Image();
        $image->setContent($content);
        $image->setMarketItem($item);
        return $image;
    }

}