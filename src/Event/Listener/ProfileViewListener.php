<?php

namespace App\Event\Listener;

use App\Entity\ProfileView;
use App\Entity\User;
use App\Repository\ProfileViewRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;

#[AsEventListener(event: RequestEvent::class, method: 'resolveProfileView')]
class ProfileViewListener
{
    private const USER_PROFILE_ROUTE = 'user_profile';

    public function __construct(
        private readonly Security $security,
        private readonly UserRepository $userRepository,
        private readonly ProfileViewRepository $profileViewRepository
    ) {
    }

    public function resolveProfileView(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $currentUser = $this->security->getUser();
        if (! $currentUser instanceof User) {
            return;
        }

        $route = $request->get('_route');
        if ($route !== self::USER_PROFILE_ROUTE) {
            return;
        }

        $targetHandle = $request->attributes->get('handle');
        $target = $this->userRepository->findOneBy([
            'handle' => $targetHandle,
        ]);

        if (! $target instanceof User) {
            return;
        }

        if ($target === $currentUser) {
            return;
        }

        $profileView = new ProfileView(owner: $currentUser, target: $target);
        $this->profileViewRepository->save(entity: $profileView, flush: true);
    }
}
