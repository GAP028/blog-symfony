<?php

namespace App\Controller;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/comments', name: 'admin_comment_')]
#[IsGranted('ROLE_ADMIN')]
final class AdminCommentController extends AbstractController
{
    #[Route('', name: 'list')]
    public function listComments(EntityManagerInterface $entityManager): Response
    {
        $comments = $entityManager->getRepository(Comment::class)->findAll();

        return $this->render('admin_comment/list.html.twig', [
            'comments' => $comments,
        ]);
    }

    #[Route('/{id}/approve', name: 'approve')]
    public function approveComment(Comment $comment, EntityManagerInterface $entityManager): Response
    {
        $comment->setStatus('validé');
        $entityManager->flush();

        return $this->redirectToRoute('admin_comment_list');
    }

    #[Route('/{id}/reject', name: 'reject')]
    public function rejectComment(Comment $comment, EntityManagerInterface $entityManager): Response
    {
        $comment->setStatus('refusé');
        $entityManager->flush();

        return $this->redirectToRoute('admin_comment_list');
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function deleteComment(Comment $comment, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($comment);
        $entityManager->flush();

        return $this->redirectToRoute('admin_comment_list');
    }
}