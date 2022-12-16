<?php

declare(strict_types = 1);

namespace App\Controller\Controller;

use App\Enum\ModeEnum;
use App\Service\CurrentUserService;
use App\Service\ModeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FeedController extends AbstractController
{
    public function __construct(
        private readonly CurrentUserService $currentUserService,
        private readonly HttpClientInterface $wikipediaApiClient,
        private readonly ModeService $modeService
    ) {
    }

    #[Route(path: '/feed', name: 'feed')]
    public function index(): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());

        $wikiResponse = $this->wikipediaApiClient->request(Request::METHOD_GET, '', [
            'query' => [
                'page' => 'Twitter',
                'format' => 'json',
                'action' => 'parse',
            ],
        ]);

        if ($this->modeService->getMode() === ModeEnum::LEARN->value) {
            $wikiResponse = $this->wikipediaApiClient->request(Request::METHOD_GET, '', [
                'query' => [
                    'page' => 'Learning',
                    'format' => 'json',
                    'action' => 'parse',
                ],
            ]);
        }

        $wikiJson = json_decode($wikiResponse->getContent(), true);

        return $this->render('user/feed.html.twig', [
            'wiki' => $wikiJson['parse']['text']['*'],
        ]);
    }
}
