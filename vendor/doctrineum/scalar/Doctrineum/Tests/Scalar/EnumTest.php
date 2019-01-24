<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace Doctrineum\Tests\Scalar;

use Doctrineum\Scalar\ScalarEnumInterface;
use Granam\Scalar\ScalarInterface;
use PHPUnit\Framework\TestCase;

class EnumTest extends TestCase
{
    /**
     * @test
     */
    public function I_can_use_enum_interface_as_scalar(): void
    {
        self::assertTrue(is_a(ScalarEnumInterface::class, ScalarInterface::class, true));
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function I_got_enums_comparison_method(): void
    {
        $enumReflection = new \ReflectionClass(ScalarEnumInterface::class);
        $isMethod = $enumReflection->getMethod('is');
        $parameters = $isMethod->getParameters();
        self::assertCount(2, $parameters);
        /** @var \ReflectionParameter $enumAsParameter */
        $enumAsParameter = \reset($parameters);
        self::assertFalse($enumAsParameter->isOptional());
        $sameClassParameter = \end($parameters);
        self::assertTrue($sameClassParameter->isOptional());
        self::assertTrue($sameClassParameter->getDefaultValue());
    }
}