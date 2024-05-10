<?php

namespace App\Controller\Controller;

use App\Service\RandomService\Contract\RandomGeneratorInterface;
use App\Service\RandomService\RandomQuoteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuoteController extends AbstractController
{
    public function __construct(
        #[Autowire(service: RandomQuoteService::class)]
        private readonly RandomGeneratorInterface $randomQuoteService
    ) {
    }

    #[Route(path: '/quote', name: 'get_single_quote')]
    public function index(): Response
    {
        return $this->render('quote/show.html.twig', [
            'quote' => $this->randomQuoteService->generate(),
        ]);
    }
}
