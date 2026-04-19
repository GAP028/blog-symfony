<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Service\AdminLogger;
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
    public function approveComment(
        Comment $comment,
        EntityManagerInterface $entityManager,
        AdminLogger $adminLogger
    ): Response {
        $comment->setStatus('validé');

        $adminLogger->log(
            'commentaire',
            'Commentaire #' . $comment->getId(),
            'validation_commentaire',
            $this->getUser()->getUserIdentifier(),
            sprintf("Le commentaire #%d a été validé.", $comment->getId())
        );

        $entityManager->flush();

        $this->addFlash('success', 'Le commentaire a été validé.');

        return $this->redirectToRoute('admin_comment_list');
    }

    #[Route('/{id}/reject', name: 'reject')]
    public function rejectComment(
        Comment $comment,
        EntityManagerInterface $entityManager,
        AdminLogger $adminLogger
    ): Response {
        $comment->setStatus('refusé');

        $adminLogger->log(
            'commentaire',
            'Commentaire #' . $comment->getId(),
            'refus_commentaire',
            $this->getUser()->getUserIdentifier(),
            sprintf("Le commentaire #%d a été refusé.", $comment->getId())
        );

        $entityManager->flush();

        $this->addFlash('warning', 'Le commentaire a été refusé.');

        return $this->redirectToRoute('admin_comment_list');
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function deleteComment(
        Comment $comment,
        EntityManagerInterface $entityManager,
        AdminLogger $adminLogger
    ): Response {
        $commentId = $comment->getId();

        $adminLogger->log(
            'commentaire',
            'Commentaire #' . $commentId,
            'suppression_commentaire',
            $this->getUser()->getUserIdentifier(),
            sprintf("Le commentaire #%d a été supprimé.", $commentId)
        );

        $entityManager->remove($comment);
        $entityManager->flush();

        $this->addFlash('warning', 'Le commentaire a bien été supprimé.');

        return $this->redirectToRoute('admin_comment_list');
    }
}