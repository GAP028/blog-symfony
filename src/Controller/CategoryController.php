<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Service\AdminLogger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/category', name: 'category_')]
final class CategoryController extends AbstractController
{
    #[Route('/new', name: 'new')]
    #[IsGranted('ROLE_ADMIN')]
    public function newCategory(
        Request $request,
        EntityManagerInterface $entityManager,
        AdminLogger $adminLogger
    ): Response {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);

            $adminLogger->log(
                'categorie',
                $category->getName(),
                'creation_categorie',
                $this->getUser()->getUserIdentifier(),
                sprintf("La catégorie '%s' a été créée.", $category->getName())
            );

            $entityManager->flush();

            $this->addFlash('success', 'La catégorie a bien été créée.');

            return $this->redirectToRoute('category_list');
        }

        return $this->render('category/form.html.twig', [
            'form' => $form->createView(),
            'pageTitle' => 'Créer une maison / catégorie',
        ]);
    }

    #[Route('/list', name: 'list')]
    public function listCategory(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Category::class)->findAll();

        return $this->render('category/list.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit')]
    #[IsGranted('ROLE_ADMIN')]
    public function editCategory(
        Category $category,
        Request $request,
        EntityManagerInterface $entityManager,
        AdminLogger $adminLogger
    ): Response {
        $oldName = $category->getName();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adminLogger->log(
                'categorie',
                $category->getName(),
                'modification_categorie',
                $this->getUser()->getUserIdentifier(),
                sprintf("La catégorie '%s' a été modifiée. Ancien nom : '%s'.", $category->getName(), $oldName)
            );

            $entityManager->flush();

            $this->addFlash('success', 'La catégorie a bien été modifiée.');

            return $this->redirectToRoute('category_list');
        }

        return $this->render('category/form.html.twig', [
            'form' => $form->createView(),
            'pageTitle' => 'Modifier une maison / catégorie',
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteCategory(
        Category $category,
        EntityManagerInterface $entityManager,
        AdminLogger $adminLogger
    ): Response {
        $name = $category->getName();

        $adminLogger->log(
            'categorie',
            $name,
            'suppression_categorie',
            $this->getUser()->getUserIdentifier(),
            sprintf("La catégorie '%s' a été supprimée.", $name)
        );

        $entityManager->remove($category);
        $entityManager->flush();

        $this->addFlash('success', 'La catégorie a bien été supprimée.');

        return $this->redirectToRoute('category_list');
    }
}