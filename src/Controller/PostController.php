<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/post')]
class PostController extends AbstractController
{
    #[Route('/create')]
    public function create (Request $request, EntityManagerInterface $em) : Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('app_post_list');
        }

        return $this->renderForm('Post/form.html.twig', [
            'form' => $form,
            'button' => 'Create'
        ]);
    }

    #[Route('/update/{post}')]
    public function update (Post $post, Request $request, EntityManagerInterface $em) : Response
    {
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('app_post_list');
        }

        return $this->renderForm('Post/form.html.twig', [
            'form' => $form,
            'button' => 'Update'
        ]);
    }

    #[Route('/delete/{post}')]
    public function delete (Post $post, EntityManagerInterface $em) : Response
    {
        $em->remove($post);
        $em->flush();
        return $this->redirectToRoute('app_post_list');
    }

    #[Route('/all')]
    function list (PostRepository $postRepository) : Response
    {
        $posts = $postRepository->findAll();
        return $this->render('Post/list.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/{post}')]
    public function view (Post $post, Request $request, EntityManagerInterface $em) : Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setPost($post);
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('app_post_view', ['post' => $post->getId()]);
        }

        return $this->renderForm('Post/view.html.twig', [
            'form' => $form,
            'post' => $post
        ]);
    }
}