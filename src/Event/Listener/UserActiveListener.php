<?php

namespace App\Event\Listener;

use App\Entity\User;
use App\Repository\UserRepository;
use Carbon\CarbonImmutable;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;

#[AsEventListener(event: RequestEvent::class, method: 'updateLastActiveAt')]
class UserActiveListener
{
    public function __construct(
        private readonly Security $security,
        private readonly UserRepository $userRepository,
    ) {
    }

    public function updateLastActiveAt(RequestEvent $event): void
    {
        $currentUser = $this->security->getUser();
        if (! $currentUser instanceof User) {
            return;
        }

        $currentUser->setLastActiveAt(new CarbonImmutable());
        $this->userRepository->save(entity: $currentUser, flush: true);
    }
}
