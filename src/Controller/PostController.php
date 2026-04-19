<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Comment;
use App\Form\PostType;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/post', name: 'post_')]
final class PostController extends AbstractController
{
    #[Route('/new', name: 'new')]
    #[IsGranted('ROLE_ADMIN')]
    public function newPost(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setAuthor($this->getUser());

            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('post_list');
        }

        return $this->render('post/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/list', name: 'list')]
    public function listPost(EntityManagerInterface $entityManager): Response
    {
        $posts = $entityManager->getRepository(Post::class)->findAll();

        return $this->render('post/list.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function showPost(Post $post): Response
    {
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);

        return $this->render('post/show.html.twig', [
            'post' => $post,
            'commentForm' => $commentForm->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'edit')]
    #[IsGranted('ROLE_ADMIN')]
    public function editPost(
        Post $post,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('post_list');
        }

        return $this->render('post/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deletePost(
        Post $post,
        EntityManagerInterface $entityManager
    ): Response {
        $entityManager->remove($post);
        $entityManager->flush();

        return $this->redirectToRoute('post_list');
    }
}