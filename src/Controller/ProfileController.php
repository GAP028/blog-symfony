<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/profile', name: 'profile_')]
#[IsGranted('ROLE_USER')]
final class ProfileController extends AbstractController
{
    #[Route('', name: 'show')]
    public function show(): Response
    {
        return $this->render('profile/show.html.twig');
    }
}