<?php

declare(strict_types=1);

namespace App\Service\ImageUploadService;

use App\Service\ImageUploadService\Contract\ImageUploadServiceInterface;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class AvatarUploadService implements ImageUploadServiceInterface
{
    public function process(UploadedFile $file): Image
    {
        $manager = new ImageManager([
            'driver' => 'imagick',
        ]);
        $image = $manager->make($file->getRealPath());
        $image->fit(400, 400);
        return $image->encode('data-url');
    }
}
