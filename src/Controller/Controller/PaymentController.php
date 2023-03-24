<?php

namespace App\Controller\Controller;

use App\Form\Payment\CardFormType;
use App\Model\Card;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/payment')]
class PaymentController extends AbstractController
{
    #[Route('/add', name: 'create_payment')]
    public function create(Request $request) : Response
    {
        $card = new Card();
        $paymentForm = $this->createForm(CardFormType::class, $card);
        $paymentForm->handleRequest($request);

        if ($paymentForm->isSubmitted() && $paymentForm->isValid()) {
            // do something..
        }

        return $this->render('payment/new.html.twig', [
            'cardForm' => $paymentForm,
        ]);

    }

}