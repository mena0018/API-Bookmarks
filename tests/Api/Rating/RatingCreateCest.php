<?php

namespace App\Tests\Api\Rating;

use App\Entity\Rating;
use App\Factory\BookmarkFactory;
use App\Factory\RatingFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ApiTester;

class RatingCreateCest
{
    protected static function expectedProperties(): array
    {
        return [
            'id' => 'integer',
            'user' => 'string:path',
            'bookmark' => 'string:path',
            'value' => 'integer',
        ];
    }

    public function anonymousUserCantCreateRating(ApiTester $I): void
    {
        // 1. 'Arrange'
        UserFactory::createOne();

        // 2. 'Act'
        $I->sendPost('/api/ratings');

        // 3. 'Assert'
        $I->seeResponseCodeIs(401);
    }

    public function authenticatedUserCanCreateRating(ApiTester $I): void
    {
        // 1. 'Arrange'
        BookmarkFactory::createOne();
        $user = UserFactory::createOne()->object();
        $I->amLoggedInAs($user);
        $data = [
                'user' => '/api/users/1',
                'bookmark' => '/api/bookmarks/1',
                'value' => 5,
        ];

        // 2. 'Act'
        $I->sendPost('/api/ratings', $data);

        // 3. 'Assert'
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(Rating::class, '/api/ratings/1');
        $I->seeResponseIsAnItem(self::expectedProperties(), $data);
    }

    public function authenticatedUserCantCreateRatingForBookmark(ApiTester $I): void
    {
        // 1. 'Arrange'
        $bookmark = BookmarkFactory::createOne();
        $user = UserFactory::createOne()->object();
        $I->amLoggedInAs($user);
        RatingFactory::createOne([
            'user' => $user,
            'bookmark' => $bookmark,
            'value' => 5,
        ]);

        // 2. 'Act'
        $I->sendPost('/api/ratings', [
            'user' => '/api/users/1',
            'bookmark' => '/api/bookmarks/1',
            'value' => 5,
        ]);

        // 3. 'Assert'
        $I->seeResponseCodeIs(422);
    }

    public function authenticatedUserCantCreateNegativeRating(ApiTester $I): void
    {
        // 1. 'Arrange'
        BookmarkFactory::createOne();
        $user = UserFactory::createOne()->object();
        $I->amLoggedInAs($user);

        // 2. 'Act'
        $I->sendPost('/api/ratings', [
            'user' => '/api/users/1',
            'bookmark' => '/api/bookmarks/1',
            'value' => -5,
        ]);

        // 3. 'Assert'
        $I->seeResponseCodeIs(422);
    }

    public function authenticatedUserCantCreateRatingGreaterThan10(ApiTester $I): void
    {
        // 1. 'Arrange'
        BookmarkFactory::createOne();
        $user = UserFactory::createOne()->object();
        $I->amLoggedInAs($user);

        // 2. 'Act'
        $I->sendPost('/api/ratings', [
            'user' => '/api/users/1',
            'bookmark' => '/api/bookmarks/1',
            'value' => 11,
        ]);

        // 3. 'Assert'
        $I->seeResponseCodeIs(422);
    }

    public function authenticatedUserCantCreateForOthers(ApiTester $I): void
    {
        // 1. 'Arrange'
        $user = UserFactory::createOne()->object();
        $I->amLoggedInAs($user);
        BookmarkFactory::createOne();
        UserFactory::createOne();

        // 2. 'Act'
        $I->sendPost('/api/ratings', [
            'user' => '/api/users/2',
            'bookmark' => '/api/bookmarks/1',
            'value' => 5,
        ]);

        // 3. 'Assert'
        $I->seeResponseCodeIs(422);
    }
}
