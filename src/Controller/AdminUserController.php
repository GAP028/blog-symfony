<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminUserType;
use App\Service\AdminLogger;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/users', name: 'admin_user_')]
#[IsGranted('ROLE_ADMIN')]
final class AdminUserController extends AbstractController
{
    #[Route('', name: 'list')]
    public function listUsers(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('admin_user/list.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/new', name: 'new')]
    public function newUser(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        FileUploader $fileUploader,
        AdminLogger $adminLogger
    ): Response {
        $user = new User();

        $form = $this->createForm(AdminUserType::class, $user, [
            'is_edit' => false,
            'current_role' => 'ROLE_USER',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $selectedRole = $form->get('role')->getData();
            $profilePictureFile = $form->get('profilePictureFile')->getData();

            $user->setPassword(
                $passwordHasher->hashPassword($user, $plainPassword)
            );

            if ($profilePictureFile) {
                $filename = $fileUploader->upload($profilePictureFile, 'profiles');
                $user->setProfilePicture('uploads/profiles/' . $filename);
            }

            $user->setRoles([$selectedRole]);
            $user->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($user);

            $adminLogger->log(
                'utilisateur',
                $user->getEmail(),
                'creation_utilisateur',
                $this->getUser()->getUserIdentifier(),
                sprintf("L'utilisateur %s a été créé.", $user->getEmail())
            );

            $entityManager->flush();

            $this->addFlash('success', 'L’utilisateur a bien été ajouté.');

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin_user/form.html.twig', [
            'form' => $form->createView(),
            'pageTitle' => 'Ajouter un utilisateur',
        ]);
    }

    #[Route('/{id}/edit', name: 'edit')]
    public function editUser(
        User $user,
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        FileUploader $fileUploader,
        AdminLogger $adminLogger
    ): Response {
        $currentRole = in_array('ROLE_ADMIN', $user->getRoles(), true) ? 'ROLE_ADMIN' : 'ROLE_USER';
        $oldEmail = $user->getEmail();

        $form = $this->createForm(AdminUserType::class, $user, [
            'is_edit' => true,
            'current_role' => $currentRole,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $selectedRole = $form->get('role')->getData();
            $profilePictureFile = $form->get('profilePictureFile')->getData();

            if (!empty($plainPassword)) {
                $user->setPassword(
                    $passwordHasher->hashPassword($user, $plainPassword)
                );
            }

            if ($profilePictureFile) {
                $filename = $fileUploader->upload($profilePictureFile, 'profiles');
                $user->setProfilePicture('uploads/profiles/' . $filename);
            }

            $user->setRoles([$selectedRole]);
            $user->setUpdatedAt(new \DateTimeImmutable());

            $adminLogger->log(
                'utilisateur',
                $user->getEmail(),
                'modification_utilisateur',
                $this->getUser()->getUserIdentifier(),
                sprintf("L'utilisateur %s a été modifié. Ancien email : %s.", $user->getEmail(), $oldEmail)
            );

            $entityManager->flush();

            $this->addFlash('success', 'L’utilisateur a bien été modifié.');

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin_user/form.html.twig', [
            'form' => $form->createView(),
            'pageTitle' => 'Modifier un utilisateur',
        ]);
    }

    #[Route('/{id}/toggle', name: 'toggle')]
    public function toggleUser(
        User $user,
        EntityManagerInterface $entityManager,
        AdminLogger $adminLogger
    ): Response {
        $newState = !$user->isActive();
        $user->setIsActive($newState);

        $adminLogger->log(
            'utilisateur',
            $user->getEmail(),
            $newState ? 'activation_compte' : 'desactivation_compte',
            $this->getUser()->getUserIdentifier(),
            sprintf(
                "Le compte %s a été %s par l'administrateur %s.",
                $user->getEmail(),
                $newState ? 'activé' : 'désactivé',
                $this->getUser()->getUserIdentifier()
            )
        );

        $entityManager->flush();

        $this->addFlash(
            'success',
            sprintf(
                "Le compte %s a bien été %s par %s.",
                $user->getEmail(),
                $newState ? 'activé' : 'désactivé',
                $this->getUser()->getUserIdentifier()
            )
        );

        return $this->redirectToRoute('admin_user_list');
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function deleteUser(
        User $user,
        EntityManagerInterface $entityManager,
        AdminLogger $adminLogger
    ): Response {
        $targetEmail = $user->getEmail();

        $adminLogger->log(
            'utilisateur',
            $targetEmail,
            'suppression_compte',
            $this->getUser()->getUserIdentifier(),
            sprintf(
                "Le compte %s a été supprimé par l'administrateur %s.",
                $targetEmail,
                $this->getUser()->getUserIdentifier()
            )
        );

        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash(
            'warning',
            sprintf(
                "Le compte %s a été supprimé par %s.",
                $targetEmail,
                $this->getUser()->getUserIdentifier()
            )
        );

        return $this->redirectToRoute('admin_user_list');
    }
}