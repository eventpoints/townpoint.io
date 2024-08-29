<?php

namespace App\Service\ImageUploadService;

use App\Service\ImageUploadService\Contract\ImageUploadServiceInterface;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class PhotoUploadService implements ImageUploadServiceInterface
{
    public function process(UploadedFile $file): Image
    {
        $manager = new ImageManager([
            'driver' => 'imagick',
        ]);
        $image = $manager->make($file->getRealPath());

        $image->resize(800, 800, function ($constraint): void {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        return $image->encode('data-url');
    }

}