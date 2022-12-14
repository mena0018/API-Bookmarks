<?php

namespace App\Tests\Api\Rating;

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
}
