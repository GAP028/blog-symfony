<?php

namespace App\Service;

use App\Entity\AdminActionLog;
use Doctrine\ORM\EntityManagerInterface;

class AdminLogger
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function log(
        string $targetType,
        string $targetIdentifier,
        string $action,
        string $performedByEmail,
        ?string $details = null
    ): void {
        $log = new AdminActionLog();
        $log->setTargetType($targetType);
        $log->setTargetIdentifier($targetIdentifier);
        $log->setAction($action);
        $log->setPerformedByEmail($performedByEmail);
        $log->setCreatedAt(new \DateTimeImmutable());
        $log->setDetails($details);

        $this->entityManager->persist($log);
    }
}