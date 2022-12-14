<?php

namespace App\Tests\Api\Rating;

use App\Factory\UserFactory;
use App\Tests\Support\ApiTester;

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

}
