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

    public function anonymousUserCantCreateNote(ApiTester $I): void
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
}
