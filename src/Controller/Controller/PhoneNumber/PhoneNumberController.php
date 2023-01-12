<?php

declare(strict_types = 1);

namespace App\Controller\Controller\PhoneNumber;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\PhoneNumber;
use App\Form\PhoneNumberFormType;
use App\Form\SelectUserPhoneNumberFormType;
use App\Repository\MessageRepository;
use App\Repository\PhoneNumberRepository;
use App\Service\CurrentUserService;
use App\ValueObject\FlashValueObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/phone-number')]
class PhoneNumberController extends AbstractController
{
    public function __construct(
        private readonly CurrentUserService $currentUserService,
        private readonly PhoneNumberRepository $phoneNumberRepository,
        private readonly MessageRepository $messageRepository,
    ) {
    }

    #[Route(path: '/show/{id}', name: 'show_phone_number')]
    public function show(PhoneNumber $phoneNumber): Response
    {
        return $this->render('/phone-number/show.html.twig', [
            'phoneNumber' => $phoneNumber,
        ]);
    }

    #[Route(path: '/edit/{id}', name: 'edit_phone_number')]
    public function edit(PhoneNumber $phoneNumber, Request $request): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        $phoneNumberForm = $this->createForm(PhoneNumberFormType::class, $phoneNumber);
        $phoneNumberForm->handleRequest($request);

        if ($phoneNumberForm->isSubmitted() && $phoneNumberForm->isValid()) {
            $this->phoneNumberRepository->add($phoneNumber, true);
            $this->addFlash(FlashValueObject::TYPE_SUCCESS, 'changes saved');

            return $this->redirectToRoute('account', [
                '_fragment' => 'phone-numbers',
            ]);
        }

        return $this->render('phone-number/edit.html.twig', [
            'phoneNumber' => $phoneNumber,
            'phoneNumberForm' => $phoneNumberForm->createView(),
        ]);
    }

    #[Route(path: '/delete/{id}', name: 'delete_phone_number')]
    public function delete(PhoneNumber $phoneNumber): Response
    {
        $this->phoneNumberRepository->remove($phoneNumber, true);
        $this->addFlash(FlashValueObject::TYPE_SUCCESS, 'changed saved');

        return $this->redirectToRoute('account');
    }

    #[Route(path: '/new', name: 'create_phone_number')]
    public function new(Request $request): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        $phoneNumber = new PhoneNumber();
        $phoneNumber->setOwner($currentUser);
        $phoneNumberForm = $this->createForm(PhoneNumberFormType::class, $phoneNumber);

        $phoneNumberForm->handleRequest($request);
        if ($phoneNumberForm->isSubmitted() && $phoneNumberForm->isValid()) {
            $this->phoneNumberRepository->add($phoneNumber, true);
            $this->addFlash(FlashValueObject::TYPE_SUCCESS, 'changes saved');

            return $this->redirectToRoute('account', [
                '_fragment' => 'phone-numbers',
            ]);
        }

        return $this->render('phone-number/new.html.twig', [
            'phoneNumber' => $phoneNumber,
            'phoneNumberForm' => $phoneNumberForm->createView(),
        ]);
    }

    #[Route(path: '/share/{id}', name: 'conversation_share_phone_number')]
    public function select(Conversation $conversation, Request $request): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        $phoneNumberForm = $this->createForm(SelectUserPhoneNumberFormType::class);
        $phoneNumberForm->handleRequest($request);
        if ($phoneNumberForm->isSubmitted() && $phoneNumberForm->isValid()) {
            $phoneNumber = $phoneNumberForm->get('phoneNumber')
                ->getData();
            $message = new Message();
            $message->setUser($currentUser);
            $message->setConversation($conversation);
            $message->setContent($phoneNumber->getCountryCode() . $phoneNumber->getContent());
            $this->messageRepository->add($message, true);

            return $this->redirectToRoute('conversation', [
                'id' => $conversation->getId(),
            ]);
        }

        return $this->render('phone-number/select.html.twig', [
            'conversation' => $conversation,
            'phoneNumberForm' => $phoneNumberForm->createView(),
        ]);
    }
}
