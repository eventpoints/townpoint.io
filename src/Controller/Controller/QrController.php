<?php

declare(strict_types = 1);

namespace App\Controller\Controller;

use App\Service\CurrentUserService;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route(path: '/qr')]
class QrController extends AbstractController
{
    public function __construct(
        private readonly CurrentUserService $currentUserService,
        private readonly UrlGeneratorInterface $urlGenerator
    ) {
    }

    #[Route(path: '/user/account', name: 'user_account_qr')]
    public function show(): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        $url = $this->urlGenerator->generate('profile', [
            'id' => $currentUser->getId(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $qr = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($url)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(500)
            ->margin(10)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->validateResult(false)
            ->build();

        return $this->render('user/qr.html.twig', [
            'qr' => $qr->getDataUri(),
        ]);
    }
}
