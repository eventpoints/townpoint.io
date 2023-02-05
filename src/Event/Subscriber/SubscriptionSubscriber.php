<?php

declare(strict_types=1);

namespace App\Event\Subscriber;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SubscriptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly Security              $security,
        private readonly UrlGeneratorInterface $urlGenerator
    )
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $user = $this->security->getUser();
        if ($user instanceof User) {
            $request = $event->getRequest();
            $route = $request->attributes->get('_route');
            $frame = $request->headers->get('Turbo-Frame');

            if ($frame) {
                return;
            }

            if ($route === 'create_subscription') {
                return;
            }

            if ($user->getIsEnabled() === false) {
                return;
                $url = $this->urlGenerator->generate("create_subscription");
                $event->setResponse(new RedirectResponse($url));
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 1]],
        ];
    }
}
