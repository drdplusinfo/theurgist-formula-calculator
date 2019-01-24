<?php
declare(strict_types=1);
/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */

namespace Doctrineum\Tests\Scalar\Helpers;

use Doctrineum\Scalar\ScalarEnumInterface;
use Granam\Strict\Object\StrictObject;

class TestSubTypeScalarEnum extends StrictObject implements ScalarEnumInterface
{
    public static function getEnum($enumValue): TestSubTypeScalarEnum
    {
        static $instances;
        if ($instances === null || ($instances[$enumValue] ?? null) === null) {
            $instances[$enumValue] = new static($enumValue);
        }

        return $instances[$enumValue];
    }

    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function is(ScalarEnumInterface $enum, bool $sameClassOnly = true): bool
    {
        return $this->getValue() === $enum->getValue()
            && (!$sameClassOnly || static::class === \get_class($enum));
    }

    public function __toString()
    {
        return (string)$this->getValue();
    }

    public function getValue()
    {
        return $this->value;
    }

}