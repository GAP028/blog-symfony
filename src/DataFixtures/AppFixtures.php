<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // ADMIN
        $admin = new User();
        $admin->setEmail('admin@test.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword(
            $this->passwordHasher->hashPassword($admin, 'Admin1234')
        );
        $admin->setFirstName('Admin');
        $admin->setLastName('Principal');
        $admin->setProfilePicture('https://images.unsplash.com/photo-1500648767791-00dcc994a43e');
        $admin->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($admin);

        // USER NORMAL
        $user = new User();
        $user->setEmail('user@test.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, 'User1234')
        );
        $user->setFirstName('Utilisateur');
        $user->setLastName('Normal');
        $user->setProfilePicture('https://images.unsplash.com/photo-1494790108377-be9c29b29330');
        $user->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($user);

        // CATEGORIES
        $categories = [];

        for ($i = 0; $i < 5; $i++) {
            $category = new Category();
            $category->setName($faker->unique()->word());
            $category->setDescription($faker->sentence(10));
            $manager->persist($category);
            $categories[] = $category;
        }

        // POSTS
        for ($i = 0; $i < 10; $i++) {
            $post = new Post();
            $post->setTitle($faker->sentence(6));
            $post->setContent($faker->paragraphs(3, true));
            $post->setPublishedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeThisYear()));
            $post->setPicture('https://picsum.photos/seed/post' . $i . '/900/500');
            $post->setAuthor($admin);
            $post->setCategory($categories[array_rand($categories)]);
            $manager->persist($post);
        }

        $manager->flush();
    }
}