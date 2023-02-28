<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setFirstname('Alexandre')
            ->setEmail('test@gmail.com');
        $manager->persist($user);

        $post = new Post();
        $post->setTitle('Article numero 1')
            ->setContent("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s")
            ->setCreatedBy($user);
        $manager->persist($post);

        $post = new Post();
        $post->setTitle('Article numero 2')
            ->setContent("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s")
            ->setCreatedBy($user);
        $manager->persist($post);

        $manager->flush();
    }
}
