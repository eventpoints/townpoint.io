<?php

declare(strict_types = 1);

namespace App\Controller\Controller;

use App\Entity\Address;
use App\Form\AddressFormType;
use App\Repository\AddressRepository;
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
        private readonly AddressRepository $addressRepository
    ) {
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

    #[Route(path: '/show/{id}', name: 'show_address')]
    public function show(Address $address): void
    {
        $this->render('address/show.html.twig', [
            'address' => $address,
        ]);
    }
}
