<?php

namespace App\Service\ImageUploadService\Contract;

use Intervention\Image\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ImageUploadServiceInterface
{
    public function process(UploadedFile $file): Image;

}