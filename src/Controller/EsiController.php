<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EsiController extends AbstractController
{
    function navbar (Request $request): Response
    {
        $response = $this->render('Parts/_navbar.html.twig');
        $response->setPublic();
        $response->setEtag(md5($response->getContent()));
        $response->isNotModified($request);
        return $response;
    }
}