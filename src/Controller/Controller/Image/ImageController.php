<?php

namespace App\Controller\Controller\Image;

use App\Entity\Image;
use App\Entity\Market\Item;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/image')]
class ImageController extends AbstractController
{


    #[Route(path: '/_show/{id}', name: '_show_image')]
    public function show(Image $image, Request $request): Response
    {
        return $this->render('image/_show.html.twig', [
            'image' => $image
        ]);
    }

}