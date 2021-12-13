<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const USERS = [
        [
            'username' => 'User 1',
            'password' => 'helloworld',
            'roles' => ['ROLE_CONTRIBUTOR',],
        ],
        [
            'username' => 'Administrator',
            'password' => 'foobar',
            'roles' => ['ROLE_ADMIN',],
        ],
    ];

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        foreach (static::USERS as $userData) {
            extract($userData);

            $user = new User();
            $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
            $user
                ->setUsername($username)
                ->setPassword($hashedPassword)
                ->setRoles($roles);

            $manager->persist($user);
        }
        
        $manager->flush();
    }
}
