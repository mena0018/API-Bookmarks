<?php

namespace App\Tests\Api\Rating;

use App\Entity\Rating;
use App\Factory\RatingFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class RatingPutCest
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

    public function anonymousUserForbiddenToPutRating(ApiTester $I): void
    {
        // 1. 'Arrange'
        UserFactory::createOne();
        RatingFactory::createOne();

        // 2. 'Act'
        $I->sendPut('/api/ratings/1');

        // 3. 'Assert'
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    public function authenticatedUserForbiddenToPutRatingOfOtherUser(ApiTester $I): void
    {
        // 1. 'Arrange'
        $user = UserFactory::createOne()->object();
        $I->amLoggedInAs($user);
        $user2 = UserFactory::createOne();

        RatingFactory::createOne([
            'user' => $user2,
        ]);

        // 2. 'Act'
        $I->sendPut('/api/ratings/1');

        // 3. 'Assert'
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

}
