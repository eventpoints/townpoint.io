<?php

declare(strict_types = 1);

namespace App\Controller\Controller;

use App\Entity\Listener;
use App\Entity\User;
use App\Factory\ListenerFactory;
use App\Repository\ListenerRepository;
use App\Service\CurrentUserService;
use App\ValueObject\FlashValueObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListenerController extends AbstractController
{
    public function __construct(
        private readonly ListenerFactory $listenerFactory,
        private readonly ListenerRepository $listenerRepository,
        private readonly CurrentUserService $currentUserService,
    ) {
    }

    #[Route(path: '/listeners', name: 'listeners')]
    public function index(): Response
    {
        return $this->render('listeners/index.html.twig');
    }

    #[Route(path: '/listen/{id}', name: 'listen')]
    public function listen(User $user): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());

        if ($user->getListeners()->count() >= 3) {
            $this->addFlash(FlashValueObject::TYPE_ERROR, 'sorry, this user can\'t have any more listeners');

            return $this->redirectToRoute('profile', [
                'id' => $user->getId(),
            ]);
        }

        if ($currentUser->getListening()->count() >= 30) {
            $this->addFlash(FlashValueObject::TYPE_ERROR, 'sorry, but you can\'t listen to anymore people');

            return $this->redirectToRoute('profile', [
                'id' => $user->getId(),
            ]);
        }

        $listener = $this->listenerFactory->create($currentUser, $user);
        $this->listenerRepository->add($listener, true);
        $this->addFlash(FlashValueObject::TYPE_SUCCESS, 'listening');

        return $this->redirectToRoute('profile', [
            'id' => $user->getId(),
        ]);
    }

    #[Route(path: '/unlisten/{id}', name: 'unlisten')]
    public function unlisten(User $user): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        $listener = $this->listenerRepository->findOneBy([
            'owner' => $currentUser,
            'target' => $user,
        ]);

        if (! $listener instanceof Listener) {
            return $this->redirectToRoute('index');
        }

        $this->listenerRepository->remove($listener, true);

        $this->addFlash(FlashValueObject::TYPE_SUCCESS, 'unlistening');

        return $this->redirectToRoute('profile', [
            'id' => $user->getId(),
        ]);
    }
}
