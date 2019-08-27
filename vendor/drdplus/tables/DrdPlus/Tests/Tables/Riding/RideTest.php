<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Riding;

use DrdPlus\Tables\Riding\Ride;
use Granam\Integer\IntegerInterface;
use PHPUnit\Framework\TestCase;

class RideTest extends TestCase
{
    /**
     * @test
     */
    public function I_can_use_it()
    {
        $ride = new Ride(123);
        self::assertSame(123, $ride->getValue());
        self::assertInstanceOf(IntegerInterface::class, $ride);
        self::assertSame('123', (string)$ride);
    }

    /**
     * @test
     */
    public function I_can_not_create_ride_with_non_integer()
    {
        $this->expectException(\DrdPlus\Tables\Riding\Exceptions\InvalidRideValue::class);
        $this->expectExceptionMessageRegExp('~devil-like~');
        new Ride('devil-like');
    }
}