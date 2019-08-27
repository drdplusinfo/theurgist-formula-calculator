<?php declare(strict_types=1);

namespace Granam\Tests\DiceRolls\Templates\Rollers;

use Granam\DiceRolls\Templates\Dices\Dice1d4;
use Granam\DiceRolls\Templates\Evaluators\OneToOneEvaluator;
use Granam\DiceRolls\Templates\Rollers\Roller1d4;
use Granam\DiceRolls\Templates\RollOn\NoRollOn;
use Granam\Integer\IntegerInterface;

class Roller1d4Test extends AbstractRollerTest
{
    protected function setUp(): void
    {
        $instanceProperty = new \ReflectionProperty(Roller1d4::class, 'roller1d4');
        $instanceProperty->setAccessible(true);
        $instanceProperty->setValue(null, null); // workaround for PhpUnit coverage
    }

    /**
     * @test
     */
    public function I_can_create_it()
    {
        $roller1d4 = Roller1d4::getIt();
        self::assertSame($roller1d4, Roller1d4::getIt());
        self::assertInstanceOf(Dice1d4::class, $roller1d4->getDice());
        self::assertInstanceOf(IntegerInterface::class, $roller1d4->getNumberOfStandardRolls());
        self::assertSame(1, $roller1d4->getNumberOfStandardRolls()->getValue());
        self::assertInstanceOf(OneToOneEvaluator::class, $roller1d4->getDiceRollEvaluator());
        self::assertInstanceOf(NoRollOn::class, $roller1d4->getBonusRollOn());
        self::assertInstanceOf(NoRollOn::class, $roller1d4->getMalusRollOn());
    }

    /**
     * @test
     */
    public function I_can_roll_by_it()
    {
        $roller1d4 = Roller1d4::getIt();
        $previousRoll = null;
        for ($attempt = 1; $attempt < 10; $attempt++) {
            $roll = $roller1d4->roll();
            self::assertNotSame($previousRoll, $roll);
            self::assertGreaterThanOrEqual($roller1d4->getDice()->getMinimum()->getValue(), $roll->getValue());
            self::assertLessThanOrEqual($roller1d4->getDice()->getMaximum()->getValue(), $roll->getValue());
            $previousRoll = $roll;
        }
        self::assertEquals(new Roller1d4(), $roller1d4, 'Roller has to be stateless');
    }
}