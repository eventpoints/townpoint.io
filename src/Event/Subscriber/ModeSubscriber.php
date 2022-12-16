<?php

declare(strict_types = 1);

namespace App\Event\Subscriber;

use App\Service\ModeService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ModeSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly ModeService $modeService
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $mode = $event->getRequest()
            ->getSession()
            ->get('_mode');

        if ($mode) {
            $this->modeService->setMode($mode);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }
}
