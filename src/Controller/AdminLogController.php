<?php

namespace App\Controller;

use App\Entity\AdminActionLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/logs', name: 'admin_log_')]
#[IsGranted('ROLE_ADMIN')]
final class AdminLogController extends AbstractController
{
    #[Route('', name: 'list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $logs = $entityManager->getRepository(AdminActionLog::class)->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin_log/list.html.twig', [
            'logs' => $logs,
        ]);
    }
}