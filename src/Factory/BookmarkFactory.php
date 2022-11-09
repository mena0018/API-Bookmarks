<?php

namespace App\Factory;

use App\Entity\Bookmark;
use App\Repository\BookmarkRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Bookmark>
 *
 * @method static Bookmark|Proxy createOne(array $attributes = [])
 * @method static Bookmark[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Bookmark[]|Proxy[] createSequence(array|callable $sequence)
 * @method static Bookmark|Proxy find(object|array|mixed $criteria)
 * @method static Bookmark|Proxy findOrCreate(array $attributes)
 * @method static Bookmark|Proxy first(string $sortedField = 'id')
 * @method static Bookmark|Proxy last(string $sortedField = 'id')
 * @method static Bookmark|Proxy random(array $attributes = [])
 * @method static Bookmark|Proxy randomOrCreate(array $attributes = [])
 * @method static Bookmark[]|Proxy[] all()
 * @method static Bookmark[]|Proxy[] findBy(array $attributes)
 * @method static Bookmark[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Bookmark[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static BookmarkRepository|RepositoryProxy repository()
 * @method Bookmark|Proxy create(array|callable $attributes = [])
 */
final class BookmarkFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->text(),
            'description' => self::faker()->text(),
            'creationDate' => null, // TODO add DATETIME ORM type manually
            'isPublic' => self::faker()->boolean(),
            'url' => self::faker()->text(),
            'rateAverage' => self::faker()->randomFloat(),
        ];
    }

    protected function initialize(): self
    {
        return $this ;
    }

    protected static function getClass(): string
    {
        return Bookmark::class;
    }
}
