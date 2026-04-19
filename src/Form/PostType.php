<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'label' => 'Titre de la chronique',
                'attr' => [
                    'placeholder' => 'Entrez un titre marquant...',
                ],
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu',
                'attr' => [
                    'rows' => 10,
                    'placeholder' => 'Rédigez votre chronique ici...',
                ],
            ])
            ->add('publishedAt', null, [
                'label' => 'Date de publication',
                'widget' => 'single_text',
            ])
            ->add('picture', UrlType::class, [
                'label' => 'Image de couverture (URL)',
                'required' => false,
                'attr' => [
                    'placeholder' => 'https://exemple.com/image.jpg',
                ],
            ])
            ->add('category', EntityType::class, [
                'label' => 'Maison / catégorie',
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisir une catégorie',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}