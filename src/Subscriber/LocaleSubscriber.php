<?php

namespace App\Subscriber;

use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface
{
    public $allowedLocales;

    function __construct() {
        $this->allowedLocales = ['en', 'fr'];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        if (!$locale = $request->getSession()->get('_locale')) {
            $locale = substr($request->headers->get('Accept-Language'), 0, 2);
        }

        if ($locale != null && in_array($locale, $this->allowedLocales)) {
            $request->setLocale($locale);
        }
//        else {
//            $request->setLocale($this->allowedLocales[0]);
//        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                ['onKernelRequest', 25]
            ]
        ];
    }
}