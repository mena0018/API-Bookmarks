<?php

namespace App\Factory;

use App\Entity\Rating;
use App\Repository\RatingRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Rating>
 *
 * @method static Rating|Proxy                     createOne(array $attributes = [])
 * @method static Rating[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Rating[]|Proxy[]                 createSequence(array|callable $sequence)
 * @method static Rating|Proxy                     find(object|array|mixed $criteria)
 * @method static Rating|Proxy                     findOrCreate(array $attributes)
 * @method static Rating|Proxy                     first(string $sortedField = 'id')
 * @method static Rating|Proxy                     last(string $sortedField = 'id')
 * @method static Rating|Proxy                     random(array $attributes = [])
 * @method static Rating|Proxy                     randomOrCreate(array $attributes = [])
 * @method static Rating[]|Proxy[]                 all()
 * @method static Rating[]|Proxy[]                 findBy(array $attributes)
 * @method static Rating[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 * @method static Rating[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static RatingRepository|RepositoryProxy repository()
 * @method        Rating|Proxy                     create(array|callable $attributes = [])
 */
final class RatingFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'user' => UserFactory::createOne(),
            'bookmark' => BookmarkFactory::createOne(),
            'value' => self::faker()->numberBetween(1, 10),
        ];
    }

    protected static function getClass(): string
    {
        return Rating::class;
    }
}
