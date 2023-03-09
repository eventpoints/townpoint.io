<?php

declare(strict_types = 1);

namespace App\Controller\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use What3words\Geocoder\Geocoder;

#[Route(path: '/what-three-words')]
class What3WordsController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route(path: '/coordinates', name: 'coordinates_to_what_three_words')]
    public function coordinates(Request $request): JsonResponse
    {
        $longitude = $request->get('longitude');
        $latitude = $request->get('latitude');

        $api = new Geocoder('WMJYW6GQ');
        $result = $api->convertTo3wa($latitude, $longitude);

        return $this->json($result);
    }
}
