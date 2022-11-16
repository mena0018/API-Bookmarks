<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne([
            'login' => 'user1',
            'roles' => ['ROLE_ADMIN'],
            'password' => 'test',
        ]);
        UserFactory::createOne([
            'login' => 'user2',
            'roles' => ['ROLE_ADMIN'],
            'password' => 'test',
        ]);
        UserFactory::createOne([
            'login' => 'user3',
            'roles' => ['ROLE_ADMIN'],
            'password' => 'test',
        ]);

        UserFactory::createMany(20);
    }
}
