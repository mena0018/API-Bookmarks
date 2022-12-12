<?php

namespace App\DataFixtures;

use App\Factory\BookmarkFactory;
use App\Factory\RatingFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RatingFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $users = UserFactory::repository()->findAll();
        $bookmarks = BookmarkFactory::repository()->randomRange(3, 7);

        foreach ($users as $user) {
            foreach ($bookmarks as $bookmark) {
                RatingFactory::createOne([
                    'bookmark' => $bookmark,
                    'user' => $user,
                ]);
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            BookmarkFixtures::class,
        ];
    }
}