<?php

namespace App\Tests\Api\User;

use App\Entity\User;
use App\Factory\UserFactory;
use App\Tests\Support\ApiTester;
use Codeception\Attribute\DataProvider;
use Codeception\Example;

class UserGetAvatarCest
{
    public function getAvatar(ApiTester $I): void
    {
        // 1. 'Arrange'
        $user = UserFactory::createOne();

        // 2. 'Act'
        $I->sendGet('/api/users/1/avatar');

        // 3. 'Assert'
        $I->seeResponseCodeIsSuccessful();
        $I->seeHttpHeader('Content-Type', 'image/png');
        $I->seeResponseContains(stream_get_contents($user->getAvatar(), -1, 0));
    }

    #[DataProvider('invalidDataLeadsToUnprocessableEntityProvider')]
    public function invalidDataLeadsToUnprocessableEntity(ApiTester $I, Example $example): void
    {
        // 1. 'Arrange'
        /** @var $user User */
        $user = UserFactory::createOne()->object();
        $I->amLoggedInAs($user);

        // 2. 'Act'
        $dataPut = [
            $example['property'] => $example['value'],
        ];

        $I->sendPut('/api/users/1', $dataPut);

        // 3. 'Assert'
        $I->seeResponseCodeIs(422);
        $I->seeResponseIsJson();
    }

    protected function invalidDataLeadsToUnprocessableEntityProvider(): array
    {
        return [
            ['property' => 'login', 'value' => '<&">'],
            ['property' => 'firstname', 'value' => '<&">'],
            ['property' => 'lastname', 'value' => '<&">'],
            ['property' => 'mail', 'value' => 'badmail'],
        ];
    }
}
