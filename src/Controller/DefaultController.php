<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/')]
    function hello () : Response
    {
        $response = $this->render('Default/hello.html.twig');
        $response->setPublic();
        $response->setMaxAge(86400);
        $response->headers->addCacheControlDirective('must-revalidate', true);

        return $response;
    }

    #[Route('/apple/{number}')]
    function apple (Int $number = 0) : Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('Default/apple.html.twig', [
            'number' => $number
        ]);
    }

    #[Route('/html')]
    function html () : Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('Default/html.html.twig', [
           'html' => "<h1>Ceci est un titre</h1>"
        ]);
    }
}