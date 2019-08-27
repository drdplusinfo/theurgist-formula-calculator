<?php declare(strict_types=1);

namespace Granam\DiceRolls\Templates\Rolls;

use Granam\DiceRolls\DiceRoll;
use Granam\DiceRolls\Roll;
use Granam\DiceRolls\Templates\Dices\Dice1d6;
use Granam\DiceRolls\Templates\Rollers\Roller2d6DrdPlus;
use Granam\Tools\ValueDescriber;

class Roll2d6DrdPlus extends Roll
{
    /**
     * If you need to create this from FINAL value, use @see Roller2d6DrdPlus::generateRoll()
     * @param array|DiceRoll[] $standardDiceRolls
     * @param array $bonusDiceRolls
     * @param array $malusDiceRolls
     * @throws \Granam\DiceRolls\Templates\Rolls\Exceptions\UnexpectedDice
     * @throws \Granam\DiceRolls\Templates\Rolls\Exceptions\UnexpectedNumberOfDiceRolls
     * @throws \Granam\DiceRolls\Templates\Rolls\Exceptions\UnexpectedBonus
     * @throws \Granam\DiceRolls\Templates\Rolls\Exceptions\UnexpectedMalus
     * @throws \Granam\DiceRolls\Templates\Rolls\Exceptions\MissingBonusDiceRoll
     * @throws \Granam\DiceRolls\Templates\Rolls\Exceptions\MissingMalusDiceRoll
     */
    public function __construct(array $standardDiceRolls, array $bonusDiceRolls = [], array $malusDiceRolls = [])
    {
        $this->guardDiceRollsValid($standardDiceRolls, $bonusDiceRolls, $malusDiceRolls);
        parent::__construct($standardDiceRolls, $bonusDiceRolls, $malusDiceRolls);
    }

    /**
     * @param array $standardDiceRolls
     * @param array $bonusDiceRolls
     * @param array $malusDiceRolls
     * @throws \Granam\DiceRolls\Templates\Rolls\Exceptions\UnexpectedDice
     * @throws \Granam\DiceRolls\Templates\Rolls\Exceptions\UnexpectedNumberOfDiceRolls
     * @throws \Granam\DiceRolls\Templates\Rolls\Exceptions\UnexpectedBonus
     * @throws \Granam\DiceRolls\Templates\Rolls\Exceptions\UnexpectedMalus
     * @throws \Granam\DiceRolls\Templates\Rolls\Exceptions\MissingBonusDiceRoll
     * @throws \Granam\DiceRolls\Templates\Rolls\Exceptions\MissingMalusDiceRoll
     */
    private function guardDiceRollsValid(array $standardDiceRolls, array $bonusDiceRolls, array $malusDiceRolls): void
    {
        $this->guardTwo1d6RollsForStandardRoll($standardDiceRolls);
        $this->guardBonusRollsValidity($bonusDiceRolls, $standardDiceRolls);
        $this->guardMalusRollsValidity($malusDiceRolls, $standardDiceRolls);
    }

    /**
     * @param array $standardDiceRolls
     * @throws \Granam\DiceRolls\Templates\Rolls\Exceptions\UnexpectedDice
     * @throws \Granam\DiceRolls\Templates\Rolls\Exceptions\UnexpectedNumberOfDiceRolls
     */
    private function guardTwo1d6RollsForStandardRoll(array $standardDiceRolls): void
    {
        $this->guard1d6DicesOnly($standardDiceRolls, 'standard');
        if (\count($standardDiceRolls) !== 2) {
            throw new Exceptions\UnexpectedNumberOfDiceRolls(
                'Expected exactly two standard dice rolls, got ' . \count($standardDiceRolls) . ' of them'
            );
        }
    }

    /**
     * @param array|DiceRoll[] $diceRolls
     * @param string $rollType
     * @throws \Granam\DiceRolls\Templates\Rolls\Exceptions\UnexpectedDice
     */
    private function guard1d6DicesOnly(array $diceRolls, string $rollType): void
    {
        foreach ($diceRolls as $diceRoll) {
            if (!($diceRoll->getDice() instanceof Dice1d6)) {
                throw new Exceptions\UnexpectedDice(
                    "Expected {$rollType} roll with dice " . Dice1d6::class . ', got ' . ValueDescriber::describe($diceRoll)
                    . ' with dice ' . ValueDescriber::describe($diceRoll->getDice())
                );
            }
        }
    }

    /**
     * @param array|DiceRoll[] $bonusDiceRolls
     * @param array|DiceRoll[] $standardDiceRolls
     * @throws \Granam\DiceRolls\Templates\Rolls\Exceptions\UnexpectedDice
     * @throws \Granam\DiceRolls\Templates\Rolls\Exceptions\UnexpectedBonus
     * @throws \Granam\DiceRolls\Templates\Rolls\Exceptions\MissingBonusDiceRoll
     */
    private function guardBonusRollsValidity(array $bonusDiceRolls, array $standardDiceRolls): void
    {
        if (\count($bonusDiceRolls) === 0) {
            $shouldHasBonus = true; // this is questionable in fact, see bellow
            foreach ($standardDiceRolls as $standardDiceRoll) {
                $shouldHasBonus = $shouldHasBonus && $standardDiceRoll->getValue() === 6;
            }
            if ($shouldHasBonus) {
                throw new Exceptions\MissingBonusDiceRoll('Expected at least one bonus roll');
            }

            return;
        }
        $this->guard1d6DicesOnly($bonusDiceRolls, 'bonus');
        foreach ($standardDiceRolls as $standardDiceRoll) {
            if ($standardDiceRoll->getValue() !== 6) {
                throw new Exceptions\UnexpectedBonus(
                    'Can not get a bonus roll without sixes only from standard rolls. Got standard dice rolls '
                    . \reset($standardDiceRolls)->getValue() . ' and ' . \end($standardDiceRolls)->getValue()
                );
            }
        }
    }

    /**
     * @param array|DiceRoll[] $malusDiceRolls
     * @param array|DiceRoll[] $standardDiceRolls
     * @throws \Granam\DiceRolls\Templates\Rolls\Exceptions\UnexpectedDice
     * @throws \Granam\DiceRolls\Templates\Rolls\Exceptions\UnexpectedMalus
     * @throws \Granam\DiceRolls\Templates\Rolls\Exceptions\MissingMalusDiceRoll
     */
    private function guardMalusRollsValidity(array $malusDiceRolls, array $standardDiceRolls): void
    {
        if (\count($malusDiceRolls) === 0) {
            $shouldHasMalus = true;
            foreach ($standardDiceRolls as $standardDiceRoll) {
                $shouldHasMalus = $shouldHasMalus && $standardDiceRoll->getValue() === 1;
            }
            if ($shouldHasMalus) {
                throw new Exceptions\MissingMalusDiceRoll('Expected at least one malus roll');
            }

            return;
        }
        $this->guard1d6DicesOnly($malusDiceRolls, 'malus');
        foreach ($standardDiceRolls as $standardDiceRoll) {
            if ($standardDiceRoll->getValue() !== 1) {
                throw new Exceptions\UnexpectedMalus(
                    'Can not get a malus roll without ones only from standard rolls. Got standard dice rolls '
                    . \reset($standardDiceRolls)->getValue() . ' and ' . \end($standardDiceRolls)->getValue()
                );
            }
        }
    }
}