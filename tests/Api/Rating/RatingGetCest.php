<?php

namespace App\Tests\Api\Rating;

use App\Entity\Rating;
use App\Factory\BookmarkFactory;
use App\Factory\RatingFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ApiTester;

class RatingGetCest
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

    public function getRatingDetail(ApiTester $I): void
    {
        // 1. 'Arrange'
        $user = UserFactory::createOne();
        $bookmark = BookmarkFactory::createOne();
        RatingFactory::createOne([
            'user' => $user,
            'bookmark' => $bookmark,
            'value' => 5,
        ]);

        // 2. 'Act'
        $I->sendGet('/api/ratings/1');

        // 3. 'Assert'
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(Rating::class, '/api/ratings/1');
        $I->seeResponseIsAnItem(self::expectedProperties(), [
            'user' => '/api/users/1',
            'bookmark' => '/api/bookmarks/1',
            'value' => 5,
        ]);
    }

    public function getCollection(ApiTester $I): void
    {
        // 1. 'Arrange'
        RatingFactory::createMany(3);

        // 2. 'Act'
        $I->sendGet('/api/ratings');

        // 3. 'Assert'
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsACollection(Rating::class, '/api/ratings', [
            'hydra:member' => 'array',
            'hydra:totalItems' => 'integer',
        ]);
        $jsonResponse = $I->grabJsonResponse();
        $I->assertSame(3, $jsonResponse['hydra:totalItems']);
        $I->assertCount(3, $jsonResponse['hydra:member']);
    }
}
