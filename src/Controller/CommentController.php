<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comment')]
class CommentController extends AbstractController
{
    #[Route('/all')]
    function all (CommentRepository $commentRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $comments = $commentRepository->findAll();
        return $this->render('Comment/list.html.twig', [
            'comments' => $comments
        ]);
    }
}