<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

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
     * @expectedException \DrdPlus\Tables\Riding\Exceptions\InvalidRideValue
     * @expectedExceptionMessageRegExp ~devil-like~
     */
    public function I_can_not_create_ride_with_non_integer()
    {
        new Ride('devil-like');
    }
}