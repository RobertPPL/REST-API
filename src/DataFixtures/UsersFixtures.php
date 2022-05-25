<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->passwordHasher = $hasher;
    }
    
    public function load(ObjectManager $manager): void
    {
        for($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setUsername('User_' . $i);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'Password_' . $i));
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
