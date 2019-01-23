<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace Doctrineum\SelfRegisteringType;

use Doctrine\DBAL\Types\Type;
use Granam\Strict\Object\StrictObjectTrait;
use Granam\Tools\ValueDescriber;

abstract class AbstractSelfRegisteringType extends Type
{
    use StrictObjectTrait;

    /**
     * @return bool If enum has not been registered before and was registered now
     * @throws \Doctrine\DBAL\DBALException
     */
    public static function registerSelf(): bool
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $reflection = new \ReflectionClass(static::class);
        /** @var Type $type */
        $type = $reflection->newInstanceWithoutConstructor();
        $typeName = $type->getName();
        if (static::hasType($typeName)) {
            static::checkRegisteredType($typeName);

            return false;
        }

        static::addType($typeName, static::class);

        return true;
    }

    /**
     * @param string $typeName
     * @throws \Doctrine\DBAL\DBALException
     */
    protected static function checkRegisteredType(string $typeName): void
    {
        $alreadyRegisteredType = static::getType($typeName);
        if (\get_class($alreadyRegisteredType) !== static::class) {
            throw new Exceptions\TypeNameOccupied(
                'Under type of name ' . ValueDescriber::describe($typeName) .
                ' is already registered different type ' . \get_class($alreadyRegisteredType)
                . ' than current ' . static::class . '.'
                . ' Did you forget to overload Type::getName() method?'
            );
        }
    }
}