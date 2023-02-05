<?php

declare(strict_types = 1);

namespace App\Service;

use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class ImageUploadService
{
    public function processAvatar(UploadedFile $file): Image
    {
        $manager = new ImageManager([
            'driver' => 'gd',
        ]);
        $image = $manager->make($file->getRealPath());
        $image->fit(400, 400);
        $image->iptc('');

        return $image->encode('data-url');
    }

    public function processStatementPhoto(UploadedFile $file): Image
    {
        $manager = new ImageManager([
            'driver' => 'gd',
        ]);
        $image = $manager->make($file->getRealPath());

        $image->resize(800, 800, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        return $image->encode('data-url');
    }
}
