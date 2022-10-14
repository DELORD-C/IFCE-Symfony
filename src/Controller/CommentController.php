<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comment')]
class CommentController extends AbstractController
{
    #[Route('/all')]
    function all (CommentRepository $commentRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $comments = $commentRepository->findAll();

        $lastModificationDate = new \DateTime();
        $lastModificationDate->setTimestamp(1665740973);
        $response = new Response();
        $response->setLastModified($lastModificationDate);
        $response->setPublic();

        if ($response->isNotModified($request)) {
            return $response;
        }

        return $this->render('Comment/list.html.twig', [
            'comments' => $comments
        ]);
    }
}