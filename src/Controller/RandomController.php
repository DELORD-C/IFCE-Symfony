<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/random')]
class RandomController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route('/number/{max}')]
    function number (Int $max = 100) : Response
    {
        $number = random_int(0,$max);
        return $this->render('info.html.twig', [
            'data' => $number
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/number/million', priority: 1)]
    function million () : Response
    {
        $number = random_int(0,1000000);
        return $this->render('info.html.twig', [
            'data' => $number
        ]);
    }
}