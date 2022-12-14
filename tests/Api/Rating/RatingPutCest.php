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

    public function authenticatedUserCanPutOwnData(ApiTester $I): void
    {
        // 1. 'Arrange'
        $user = UserFactory::createOne()->object();
        $I->amLoggedInAs($user);

        $dataInit = [
            'user' => $user,
            'value' => 5,
        ];
        RatingFactory::createOne($dataInit);

        // 2. 'Act'
        $dataPatch = ['value' => 7, 'user' => '/api/users/1'];
        $I->sendPut('/api/ratings/1', $dataPatch);

        // 3. 'Assert'
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(Rating::class, '/api/ratings/1');
        $I->seeResponseIsAnItem(self::expectedProperties(), $dataPatch);
    }
}
