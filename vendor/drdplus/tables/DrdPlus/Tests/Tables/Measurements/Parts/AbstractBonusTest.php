<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Measurements\Partials;

use DrdPlus\Tables\Measurements\Partials\AbstractBonus;
use Granam\Tests\Tools\TestWithMockery;
use Granam\Integer\IntegerInterface;

class AbstractBonusTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_create_bonus()
    {
        $bonus = new DeAbstractedBonus($value = 123);
        self::assertSame($value, $bonus->getValue());
    }

    /**
     * @test
     */
    public function I_can_create_bonus_from_float_without_decimal()
    {
        $bonus = new DeAbstractedBonus($value = 123.0);
        self::assertSame((int)$value, $bonus->getValue());
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Measurements\Partials\Exceptions\BonusRequiresInteger
     */
    public function I_cannot_create_bonus_from_number_with_decimal()
    {
        new DeAbstractedBonus($value = 123.456);
    }

    /**
     * @test
     */
    public function I_can_use_bonus_as_an_integer_object()
    {
        $bonus = new DeAbstractedBonus($value = 123456);
        self::assertInstanceOf(IntegerInterface::class, $bonus);
    }

}

/** inner */
class DeAbstractedBonus extends AbstractBonus
{
    public function __construct($value)
    {
        parent::__construct($value);
    }
}
