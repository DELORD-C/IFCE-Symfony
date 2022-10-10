<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/hello')]
    function hello () : Response
    {
        return $this->render('Default/hello.html.twig');
    }

    #[Route('/apple/{number}')]
    function apple (Int $number = 0) : Response
    {
        return $this->render('Default/apple.html.twig', [
            'number' => $number
        ]);
    }

    #[Route('/html')]
    function html () : Response
    {
        return $this->render('Default/html.html.twig', [
           'html' => "<h1>Ceci est un titre</h1>"
        ]);
    }
}