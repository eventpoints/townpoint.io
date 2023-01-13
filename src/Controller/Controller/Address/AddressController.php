<?php

declare(strict_types = 1);

namespace App\Controller\Controller\Address;

use App\Entity\Address;
use App\Entity\Conversation;
use App\Entity\Message;
use App\Form\AddressFormType;
use App\Form\SelectUserAddressFormType;
use App\Repository\AddressRepository;
use App\Repository\MessageRepository;
use App\Service\CurrentUserService;
use App\ValueObject\FlashValueObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/address')]
class AddressController extends AbstractController
{
    public function __construct(
        private readonly CurrentUserService $currentUserService,
        private readonly MessageRepository $messageRepository,
        private readonly AddressRepository $addressRepository
    ) {
    }

    #[Route(path: '/', name: 'addresses')]
    public function index(): Response
    {
        return $this->render('address/index.html.twig');
    }

    #[Route(path: '/create', name: 'create_address')]
    public function create(Request $request): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        $address = new Address();
        $address->setOwner($currentUser);
        $addressForm = $this->createForm(AddressFormType::class, $address);

        $addressForm->handleRequest($request);
        if ($addressForm->isSubmitted() && $addressForm->isValid()) {
            $this->addressRepository->add($addressForm->getData(), true);
            $this->addFlash(FlashValueObject::TYPE_SUCCESS, 'changes saved');

            return $this->redirectToRoute('account', [
                '_fragment' => 'addresses',
            ]);
        }

        return $this->render('address/new.html.twig', [
            'addressForm' => $addressForm->createView(),
        ]);
    }

    #[Route(path: '/edit/{id}', name: 'edit_address')]
    public function edit(Address $address, Request $request): Response
    {
        $addressForm = $this->createForm(AddressFormType::class, $address);

        $addressForm->handleRequest($request);
        if ($addressForm->isSubmitted() && $addressForm->isValid()) {
            $this->addressRepository->add($addressForm->getData(), true);
            $this->addFlash(FlashValueObject::TYPE_SUCCESS, 'changes saved');

            return $this->redirectToRoute('account', [
                '_fragment' => 'addresses',
            ]);
        }

        return $this->render('address/edit.html.twig', [
            'addressForm' => $addressForm->createView(),
        ]);
    }

    #[Route(path: '/show/{id}', name: 'show_address')]
    public function show(Address $address): void
    {
        $this->render('address/show.html.twig', [
            'address' => $address,
        ]);
    }

    #[Route(path: '/share/{id}', name: 'conversation_share_address')]
    public function select(Conversation $conversation, Request $request): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        $addressForm = $this->createForm(SelectUserAddressFormType::class);
        $addressForm->handleRequest($request);
        if ($addressForm->isSubmitted() && $addressForm->isValid()) {
            $address = $addressForm->get('address')
                ->getData();
            $message = new Message();
            $message->setUser($currentUser);
            $message->setConversation($conversation);
            $message->setContent(
                $address->getLineOne() . ', ' . $address->getLineTwo() . ',' . $address->getTownOrCity() . ',' . $address->getPostCode() . ',' . $address->getCountry()
            );
            $this->messageRepository->add($message, true);

            return $this->redirectToRoute('conversation', [
                'id' => $conversation->getId(),
            ]);
        }

        return $this->render('address/select.html.twig', [
            'conversation' => $conversation,
            'addressForm' => $addressForm->createView(),
        ]);
    }
}
