<?php

namespace App\DataFixtures;

use App\Factory\BookmarkFactory;
use App\Factory\RatingFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RatingFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach (UserFactory::all() as $user) {
            foreach (BookmarkFactory::randomRange(3, 7) as $bookmark) {
                RatingFactory::createOne([
                    'bookmark' => $bookmark,
                    'user' => $user,
                ]);
            }
        }
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            BookmarkFixtures::class,
        ];
    }
}
