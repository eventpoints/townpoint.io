<?php

declare(strict_types = 1);

namespace App\Controller\Controller\Market;

use App\Entity\Market\Classified;
use App\Entity\Market\Item;
use App\Form\ClassifiedFormType;
use App\Repository\ClassifiedRepository;
use App\Service\CurrentUserService;
use App\ValueObject\FlashValueObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/market/classified')]
class ClassifiedController extends AbstractController
{
    public function __construct(
        private readonly CurrentUserService $currentUserService,
        private readonly ClassifiedRepository $classifiedRepository,
    ) {
    }

    #[Route(path: '/create', name: 'create_market_classified')]
    public function create(Request $request): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        $classified = new Classified();

        $item = new Item();
        $classified->addItem($item);

        $classified->setOwner($currentUser);
        $classifiedForm = $this->createForm(ClassifiedFormType::class, $classified);

        $classifiedForm->handleRequest($request);
        if ($classifiedForm->isSubmitted() && $classifiedForm->isValid()) {
            $this->classifiedRepository->save($classifiedForm->getData(), true);
            $this->addFlash(FlashValueObject::TYPE_SUCCESS, 'classified created');

            return $this->redirectToRoute('create_market_classified', [
                'id' => $classified->getId(),
            ]);
        }

        return $this->render('market/classified/new.html.twig', [
            'classified' => $classified,
            'classifiedForm' => $classifiedForm->createView(),
        ]);
    }

    #[Route(path: '/show/{id}', name: 'show_market_classified')]
    public function show(Classified $classified): Response
    {
        return $this->render('market/classified/show.html.twig', [
            'classified' => $classified,
        ]);
    }
}
