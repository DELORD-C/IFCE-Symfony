<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocaleController extends AbstractController
{
    #[Route('/locale/{locale}')]
    function set(string $locale, Request $request): Response
    {
        $request->getSession()->set('_locale', $locale);
        $url = empty($request->get('route'))
            ? 'app_default_hello'
            : $request->get('route');
        return $this->redirectToRoute($url);
    }
}