<?php

declare(strict_types = 1);

namespace App\Controller\Business;

use App\Entity\Business\Business;
use App\Form\Business\BusinessType;
use App\Repository\Business\BusinessRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/business/business')]
class BusinessController extends AbstractController
{
    #[Route('/', name: 'app_business_business_index', methods: ['GET'])]
    public function index(BusinessRepository $businessRepository): Response
    {
        return $this->render('business/business/index.html.twig', [
            'businesses' => $businessRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_business_business_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BusinessRepository $businessRepository): Response
    {
        $business = new Business();
        $form = $this->createForm(BusinessType::class, $business);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $businessRepository->save($business, true);

            return $this->redirectToRoute('app_business_business_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('business/business/new.html.twig', [
            'business' => $business,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_business_business_show', methods: ['GET'])]
    public function show(Business $business): Response
    {
        return $this->render('business/business/show.html.twig', [
            'business' => $business,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_business_business_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Business $business, BusinessRepository $businessRepository): Response
    {
        $form = $this->createForm(BusinessType::class, $business);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $businessRepository->save($business, true);

            return $this->redirectToRoute('app_business_business_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('business/business/edit.html.twig', [
            'business' => $business,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_business_business_delete', methods: ['POST'])]
    public function delete(Request $request, Business $business, BusinessRepository $businessRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $business->getId(), (string)$request->request->get('_token'))) {
            $businessRepository->remove($business, true);
        }

        return $this->redirectToRoute('app_business_business_index', [], Response::HTTP_SEE_OTHER);
    }
}
