<?php declare(strict_types = 1);

namespace Granam\Tests\Boolean;

use Granam\Boolean\BooleanInterface;
use Granam\Scalar\ScalarInterface;
use PHPUnit\Framework\TestCase;

class BooleanInterfaceTest extends TestCase
{
    /**
     * @test
     */
    public function interface_exists(): void
    {
        self::assertTrue(interface_exists(BooleanInterface::class));
    }

    /**
     * @test
     */
    public function inherits_from_scalar_interface(): void
    {
        self::assertTrue(\is_a(
            BooleanInterface::class,
            ScalarInterface::class,
            true /* allow string as tested class name*/
        ));
    }
}