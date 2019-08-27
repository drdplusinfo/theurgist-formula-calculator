<?php declare(strict_types=1);

namespace Granam\Tests\DiceRolls;

use Granam\DiceRolls\Dice;
use Granam\DiceRolls\DiceRoll;
use Granam\DiceRolls\DiceRollEvaluator;
use Granam\DiceRolls\Exceptions\InvalidSequenceNumber;
use Granam\DiceRolls\Roll;
use Granam\DiceRolls\Roller;
use Granam\DiceRolls\RollOn;
use Granam\Tests\DiceRolls\Templates\Rollers\AbstractRollerTest;
use Granam\Integer\IntegerInterface;
use Granam\Integer\PositiveInteger;

class RollerTest extends AbstractRollerTest
{

    /**
     * @test
     */
    public function I_can_create_it(): void
    {
        $rollerWithMalus = new Roller(
            $dice = $this->createDice(),
            $numberOfStandardRolls = $this->createNumber(),
            $diceRollEvaluator = $this->createDiceRollEvaluator(),
            $bonusRollOn = $this->createBonusRollOn(),
            $malusRollOn = $this->createMalusRollOn()
        );
        self::assertSame($dice, $rollerWithMalus->getDice());
        self::assertSame($numberOfStandardRolls, $rollerWithMalus->getNumberOfStandardRolls());
        self::assertSame($diceRollEvaluator, $rollerWithMalus->getDiceRollEvaluator());
        self::assertSame($bonusRollOn, $rollerWithMalus->getBonusRollOn());
        self::assertSame($malusRollOn, $rollerWithMalus->getMalusRollOn());
    }

    /**
     * @param int $minimumValue
     * @param int $maximumValue
     * @return \Mockery\MockInterface|Dice
     */
    private function createDice(int $minimumValue = 1, int $maximumValue = 1)
    {
        $dice = $this->mockery(Dice::class);
        $dice->shouldReceive('getMinimum')
            ->andReturn($minimum = $this->mockery(IntegerInterface::class));
        $minimum->shouldReceive('getValue')
            ->andReturn($minimumValue);
        $dice->shouldReceive('getMaximum')
            ->andReturn($maximum = $this->mockery(IntegerInterface::class));
        $maximum->shouldReceive('getValue')
            ->andReturn($maximumValue);

        return $dice;
    }

    /**
     * @param int $number
     * @return \Mockery\MockInterface|IntegerInterface
     */
    private function createNumber(int $number = 1)
    {
        $numberOfStandardRolls = $this->mockery(IntegerInterface::class);
        $numberOfStandardRolls->shouldReceive('getValue')
            ->andReturn($number);

        return $numberOfStandardRolls;
    }

    /**
     * @param int $number
     * @return \Mockery\MockInterface|IntegerInterface
     */
    private function createPositiveNumber(int $number = 1)
    {
        self::assertGreaterThanOrEqual(0, $number);
        $numberOfStandardRolls = $this->mockery(PositiveInteger::class);
        $numberOfStandardRolls->shouldReceive('getValue')
            ->andReturn($number);

        return $numberOfStandardRolls;
    }

    /**
     * @return \Mockery\MockInterface|DiceRollEvaluator
     */
    private function createDiceRollEvaluator()
    {
        $diceRollEvaluator = $this->mockery(DiceRollEvaluator::class);
        $diceRollEvaluator->shouldReceive('evaluateDiceRoll')
            ->zeroOrMoreTimes()
            ->with(\Mockery::type(DiceRoll::class))
            ->andReturnUsing(function (DiceRoll $diceRoll) {
                return $diceRoll->getRolledNumber(); // de facto one to one
            });

        return $diceRollEvaluator;
    }

    /**
     * @param array|int[] $shouldHappenOn
     * @param int $numberOfDiceRolls = 1
     * @param Dice $dice = null
     * @return RollOn|\Mockery\MockInterface
     */
    private function createBonusRollOn(array $shouldHappenOn = [], int $numberOfDiceRolls = 1, Dice $dice = null): RollOn
    {
        return $this->createRollOn($shouldHappenOn, $numberOfDiceRolls, $dice);
    }

    /**
     * @param array|int[] $shouldHappenOn
     * @param int $numberOfDiceRolls
     * @param Dice $dice = null
     * @return \Mockery\MockInterface|RollOn
     */
    private function createRollOn(array $shouldHappenOn, int $numberOfDiceRolls, Dice $dice = null)
    {
        $rollOn = $this->mockery(RollOn::class);
        $rollOn->shouldReceive('shouldHappen')
            ->zeroOrMoreTimes()
            ->andReturnUsing(function ($value) use ($shouldHappenOn) {
                return \in_array($value, $shouldHappenOn, true);
            });
        $rollOn->shouldReceive('rollDices')
            ->zeroOrMoreTimes()
            ->with(\Mockery::type('int'))
            ->andReturnUsing(function ($rollSequenceStart) use ($numberOfDiceRolls, $dice) {
                self::assertGreaterThan(0, $rollSequenceStart);
                $diceRolls = [];
                for ($diceRollNumber = 1; $diceRollNumber <= $numberOfDiceRolls; $diceRollNumber++) {
                    $diceRoll = $this->mockery(DiceRoll::class);
                    $diceRoll->shouldReceive('getDice')
                        ->andReturn($dice);
                    $diceRoll->shouldReceive('getSequenceNumber')
                        ->andReturn($rolledNumber = $this->createPositiveNumber($rollSequenceStart + ($diceRollNumber - 1)));
                    $diceRoll->shouldReceive('getValue')
                        ->andReturn($diceRollNumber /* just some int for sum */);
                    $diceRolls[] = $diceRoll;
                }

                return $diceRolls;
            });
        $rollOn->shouldReceive('rollDices')
            ->andReturn($numberOfDiceRolls);

        return $rollOn;
    }

    /**
     * @param array $shouldHappenOn
     * @param int $numberOfDiceRolls
     * @param Dice $dice = null
     * @return RollOn|\Mockery\MockInterface
     */
    private function createMalusRollOn(array $shouldHappenOn = [], $numberOfDiceRolls = 1, Dice $dice = null)
    {
        return $this->createRollOn($shouldHappenOn, $numberOfDiceRolls, $dice);
    }

    /**
     * @test
     */
    public function I_can_not_use_strange_dice_with_minimum_greater_than_maximum()
    {
        $this->expectException(\Granam\DiceRolls\Exceptions\InvalidDiceRange::class);
        new Roller(
            $this->createDice(2, 1),
            $this->createNumber(),
            $this->createDiceRollEvaluator(),
            $this->createBonusRollOn(),
            $this->createMalusRollOn()
        );
    }

    /**
     * @test
     */
    public function I_can_not_use_zero_number_of_standard_rolls()
    {
        $this->expectException(\Granam\DiceRolls\Exceptions\InvalidNumberOfRolls::class);
        new Roller(
            $this->createDice(),
            $this->createNumber(0),
            $this->createDiceRollEvaluator(),
            $this->createBonusRollOn(),
            $this->createMalusRollOn()
        );
    }

    /**
     * @test
     */
    public function I_can_not_use_negative_number_of_standard_rolls()
    {
        $this->expectException(\Granam\DiceRolls\Exceptions\InvalidNumberOfRolls::class);
        new Roller(
            $this->createDice(),
            $this->createNumber(-1),
            $this->createDiceRollEvaluator(),
            $this->createBonusRollOn(),
            $this->createMalusRollOn()
        );
    }

    /**
     * @test
     */
    public function I_can_not_use_bonus_and_malus_with_same_triggering_values()
    {
        $this->expectException(\Granam\DiceRolls\Exceptions\BonusAndMalusChanceConflict::class);
        new Roller(
            $this->createDice(1, 5),
            $this->createNumber(),
            $this->createDiceRollEvaluator(),
            $this->createBonusRollOn([2, 3]),
            $this->createMalusRollOn([3, 4])
        );
    }

    /**
     * @test
     */
    public function I_can_roll_by_it()
    {
        $roller = new Roller(
            $dice = $this->createDice($minimumValue = 111, $maximumValue = 222),
            $this->createNumber($numberOfRollsValue = 5),
            $this->createDiceRollEvaluator(),
            $bonusRollOn = $this->createBonusRollOn(),
            $malusRollOn = $this->createMalusRollOn()
        );
        $roll = $roller->roll();

        $this->checkSummaryAndRollSequence($roll, $dice, $numberOfRollsValue);
        self::assertGreaterThanOrEqual($minimumValue * $numberOfRollsValue, $roll->getValue());
        self::assertLessThanOrEqual($maximumValue * $numberOfRollsValue, $roll->getValue());
        self::assertSame($roll->getDiceRolls(), $roll->getStandardDiceRolls());
        self::assertEquals([], $roll->getBonusDiceRolls());
        self::assertEquals([], $roll->getMalusDiceRolls());
    }

    private function checkSummaryAndRollSequence(Roll $roll, Dice $expectedDice, $numberOfRolls, $rollSequenceOffset = 0)
    {
        $summary = 0;
        $rollNumber = 0;
        $currentRollSequence = 0 + $rollSequenceOffset;
        foreach ($roll->getDiceRolls() as $diceRoll) {
            $currentRollSequence++;
            $rollNumber++;
            self::assertSame($expectedDice, $diceRoll->getDice());
            $summary += $diceRoll->getValue();
            self::assertSame(
                $currentRollSequence,
                $diceRoll->getSequenceNumber()->getValue() /* integer from the mock */,
                "Roll sequence is not successive. Expected $currentRollSequence (including offset $rollSequenceOffset)."
            );
        }
        self::assertSame($roll->getValue(), $summary);
        self::assertSame($rollNumber, $numberOfRolls);
    }

    /**
     * @test
     */
    public function I_can_roll_with_bonus()
    {
        $roller = new Roller(
            $dice = $this->createDice($minimumValue = 5, $maximumValue = 13),
            $numberOfRolls = $this->createNumber($numberOfRollsValue = 1),
            $diceRollEvaluator = $this->createDiceRollEvaluator(),
            $bonusRollOn = $this->createBonusRollOn(
                [7, 10],
                $numberOfBonusRollsValue = 3,
                $dice
            ),
            $malusRollOn = $this->createMalusRollOn()
        );
        for ($attempt = 1; $attempt < self::MAX_ROLL_ATTEMPTS; $attempt++) {
            $roll = $roller->roll($attempt /* used as roll sequence start */);
            $this->checkSummaryAndRollSequence(
                $roll,
                $dice,
                $numberOfRollsValue + \count($roll->getBonusDiceRolls()),
                $attempt - 1 /* used as sequence start offset */
            );
            if (\count($roll->getBonusDiceRolls()) > 1) { // at least 1 positive bonus roll (+ last negative bonus roll)
                break;
            }
        }
        self::assertLessThan(self::MAX_ROLL_ATTEMPTS, $attempt);
    }

    /**
     * @test
     */
    public function I_can_roll_with_malus()
    {
        $roller = new Roller(
            $dice = $this->createDice($minimumValue = 5, $maximumValue = 13),
            $numberOfRolls = $this->createNumber($numberOfRollsValue = 1),
            $diceRollEvaluator = $this->createDiceRollEvaluator(),
            $bonusRollOn = $this->createBonusRollOn(),
            $malusRollOn = $this->createMalusRollOn(
                [6, 7, 11],
                $numberOfMalusRollsValue = 4,
                $dice
            )
        );
        for ($attempt = 1; $attempt < self::MAX_ROLL_ATTEMPTS; $attempt++) {
            $roll = $roller->roll($attempt /* used as a roll sequence start */);
            $this->checkSummaryAndRollSequence(
                $roll,
                $dice,
                $numberOfRollsValue + \count($roll->getMalusDiceRolls()),
                $attempt - 1 /* used as sequence start offset */
            );
            if (\count($roll->getMalusDiceRolls()) > 1) { // at least one positive malus roll (+1 negative malus roll)
                break;
            }
        }
        self::assertLessThan(self::MAX_ROLL_ATTEMPTS, $attempt);
    }

    /**
     * @test
     */
    public function I_can_not_use_non_positive_sequence()
    {
        $roller = new Roller(
            $this->createDice(),
            $this->createNumber(),
            $this->createDiceRollEvaluator(),
            $this->createBonusRollOn(),
            $this->createMalusRollOn()
        );
        foreach ([0, -1, -2] as $prohibitedSequenceStart) {
            $exception = false;
            try {
                $roller->roll($prohibitedSequenceStart);
            } catch (InvalidSequenceNumber $exception) {
            }
            self::assertNotFalse($exception);
        }
    }
}