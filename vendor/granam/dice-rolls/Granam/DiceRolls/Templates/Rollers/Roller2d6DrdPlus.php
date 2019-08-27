<?php declare(strict_types=1);

namespace Granam\DiceRolls\Templates\Rollers;

use Granam\DiceRolls\DiceRoll;
use Granam\DiceRolls\Roll;
use Granam\DiceRolls\Roller;
use Granam\DiceRolls\Templates\DiceRolls\Dice1d6DrdPlusBonusRoll;
use Granam\DiceRolls\Templates\DiceRolls\Dice1d6DrdPlusMalusRoll;
use Granam\DiceRolls\Templates\DiceRolls\Dice1d6Roll;
use Granam\DiceRolls\Templates\Numbers\Two;
use Granam\DiceRolls\Templates\Dices\Dice1d6;
use Granam\DiceRolls\Templates\Evaluators\OneToOneEvaluator;
use Granam\DiceRolls\Templates\Rolls\Roll2d6DrdPlus;
use Granam\DiceRolls\Templates\RollOn\RollOn12;
use Granam\DiceRolls\Templates\RollOn\RollOn2;
use Granam\Integer\IntegerInterface;
use Granam\Integer\Tools\ToInteger;

/**
 * 2x1d6; 12 = bonus roll by 1x1d6 => 1-3 = 0, 4-6 = +1 and rolls again; 2 = malus roll by 1x1d6 => 1-3 = -1 and rolls again, 4-6 = 0
 * @method Roll2d6DrdPlus|Roll roll(int $sequenceStartNumber = 1): Roll
 */
class Roller2d6DrdPlus extends Roller
{

    private static $roller2d6DrdPlus;

    /**
     * @return Roller2d6DrdPlus
     */
    public static function getIt(): Roller2d6DrdPlus
    {
        if (self::$roller2d6DrdPlus === null) {
            self::$roller2d6DrdPlus = new static();
        }

        return self::$roller2d6DrdPlus;
    }

    public function __construct()
    {
        parent::__construct(
            Dice1d6::getIt(),
            Two::getIt(), // number of rolls = 2
            OneToOneEvaluator::getIt(), // rolled value remains untouched
            new RollOn12( // bonus happens on sum roll value of 12 (both standard rolls summarized)
                Roller1d6DrdPlusBonus::getIt() // bonus roll by 1d6; 1-3 = +0; 4-6 = +1; repeatedly in case of bonus
            ),
            new RollOn2( // malus happens on sum roll of 2 (both standard rolls summarized)
                Roller1d6DrdPlusMalus::getIt() // malus roll by 1d6; 1-3 = -1; 4-6 = 0; repeatedly in case of malus
            )
        );
    }

    /**
     * @param array|DiceRoll[] $standardDiceRolls
     * @param array|DiceRoll[] $bonusDiceRolls
     * @param array|DiceRoll[] $malusDiceRolls
     * @return Roll2d6DrdPlus|Roll
     */
    protected function createRoll(array $standardDiceRolls, array $bonusDiceRolls, array $malusDiceRolls): Roll
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new Roll2d6DrdPlus($standardDiceRolls, $bonusDiceRolls, $malusDiceRolls);
    }

    /**
     * Warning! This will generate roll history randomly
     *
     * @param int|IntegerInterface $rollSummary
     * @return Roll2d6DrdPlus
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     */
    public function generateRoll($rollSummary): Roll2d6DrdPlus
    {
        $rollSummary = ToInteger::toInteger($rollSummary);
        $bonusDiceRolls = [];
        $malusDiceRolls = [];
        if ($rollSummary <= 2) { // two ones = malus rolls and one "not valid" malus roll
            $standardDiceRolls = [new Dice1d6Roll(1, 1), new Dice1d6Roll(1, 2)]; // two ones = malus rolls
            $sequenceNumber = 3;
            for ($malusRollsCount = 2 - $rollSummary, $malusRollNumber = 1; $malusRollNumber <= $malusRollsCount; $malusRollNumber++) {
                /** @noinspection PhpUnhandledExceptionInspection */
                $malusDiceRolls[] = new Dice1d6DrdPlusMalusRoll(\random_int(1, 3), $sequenceNumber); // malus roll is valid only in range 1..3
                $sequenceNumber++;
            }
            /** @noinspection PhpUnhandledExceptionInspection */
            $malusDiceRolls[] = new Dice1d6DrdPlusMalusRoll(\random_int(4, 6), $sequenceNumber); // last malus roll was not "valid" - broke the chain
        } elseif ($rollSummary < 12) {
            $randomRange = 12 - $rollSummary; // 1..11
            $firstRandomMinimum = 6 - $randomRange;
            if ($firstRandomMinimum < 1) {
                $firstRandomMinimum = 1;
            }
            $firstRandomMaximum = $rollSummary - $firstRandomMinimum;
            /** @noinspection PhpUnhandledExceptionInspection */
            $firstRoll = \random_int($firstRandomMinimum, $firstRandomMaximum);
            $secondRoll = $rollSummary - $firstRoll;
            $firstDiceRoll = new Dice1d6Roll($firstRoll, 1);
            $secondDiceRoll = new Dice1d6Roll($secondRoll, 2);
            $standardDiceRolls = [$firstDiceRoll, $secondDiceRoll];
        } else { // two sixes = bonus rolls and one "not valid" bonus roll
            $standardDiceRolls = [new Dice1d6Roll(6, 1), new Dice1d6Roll(6, 2)];
            $sequenceNumber = 3;
            for ($bonusRollsCount = $rollSummary - 12, $bonusRollNumber = 1; $bonusRollNumber <= $bonusRollsCount; $bonusRollNumber++) {
                /** @noinspection PhpUnhandledExceptionInspection */
                $bonusDiceRolls[] = new Dice1d6DrdPlusBonusRoll(\random_int(4, 6), $sequenceNumber); // bonus roll is valid only in range 4..6
                $sequenceNumber++;
            }
            /** @noinspection PhpUnhandledExceptionInspection */
            $bonusDiceRolls[] = new Dice1d6DrdPlusBonusRoll(\random_int(1, 3), $sequenceNumber); // last bonus roll was not "valid" - broke the chain
        }

        return $this->createRoll($standardDiceRolls, $bonusDiceRolls, $malusDiceRolls);
    }

}