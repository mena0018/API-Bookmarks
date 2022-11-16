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
            'password' => 'test',
        ]);
        UserFactory::createOne([
            'login' => 'user2',
            'password' => 'test',
        ]);
        UserFactory::createOne([
            'login' => 'user3',
            'password' => 'test',
        ]);

        UserFactory::createMany(20);
    }
}
