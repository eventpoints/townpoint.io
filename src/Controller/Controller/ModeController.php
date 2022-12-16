<?php

declare(strict_types = 1);

namespace App\Controller\Controller;

use App\Form\ModeFormType;
use App\Service\ModeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/mode')]
class ModeController extends AbstractController
{
    public function __construct(
        private readonly ModeService $modeService,
        private readonly RequestStack $requestStack
    ) {
    }

    #[Route(path: '/set', name: 'set_mode')]
    public function form(Request $request): Response
    {
        $modeForm = $this->createForm(ModeFormType::class);
        $modeForm->handleRequest($request);

        if ($modeForm->isSubmitted() && $modeForm->isValid()) {
            $mode = $modeForm->get('mode')
                ->getData();
            $this->requestStack->getSession()
                ->set('_mode', $mode);
            $this->modeService->setMode($mode);

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('mode/new.html.twig', [
            'modeForm' => $modeForm->createView(),
        ]);
    }
}
