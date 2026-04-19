<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
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
        EntityManagerInterface $entityManager
    ): Response {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
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
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
        EntityManagerInterface $entityManager
    ): Response {
        $entityManager->remove($category);
        $entityManager->flush();

        $this->addFlash('success', 'La catégorie a bien été supprimée.');

        return $this->redirectToRoute('category_list');
    }
}