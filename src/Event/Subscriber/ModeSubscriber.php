<?php

namespace App\Event\Subscriber;

use App\Enum\ModeEnum;
use App\Service\ModeService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use function Symfony\Component\DependencyInjection\Loader\Configurator\expr;

class ModeSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private ModeService $modeService
    )
    {
    }

    public function onKernelRequest(RequestEvent $event) : void
    {
        if ($mode = $event->getRequest()->getSession()->get('_mode'))
        {
           $this->modeService->setMode($mode);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }
}