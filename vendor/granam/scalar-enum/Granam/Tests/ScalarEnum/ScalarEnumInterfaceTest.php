<?php
declare(strict_types=1);

namespace Granam\Tests\ScalarEnum;

use Granam\ScalarEnum\ScalarEnumInterface;
use Granam\Scalar\ScalarInterface;
use PHPUnit\Framework\TestCase;

class ScalarEnumInterfaceTest extends TestCase
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