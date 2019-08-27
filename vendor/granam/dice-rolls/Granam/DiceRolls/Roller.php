<?php declare(strict_types=1);

namespace Granam\DiceRolls;

use Granam\Integer\IntegerInterface;
use Granam\Integer\PositiveInteger;
use Granam\Integer\PositiveIntegerObject;
use Granam\Strict\Object\StrictObject;
use Granam\Tools\ValueDescriber;

class Roller extends StrictObject
{

    /** @var Dice */
    private $dice;
    /** @var IntegerInterface */
    private $numberOfStandardRolls;
    /** @var DiceRollEvaluator */
    private $diceRollEvaluator;
    /** @var RollOn */
    private $bonusRollOn;
    /** @var RollOn */
    private $malusRollOn;

    /**
     * @param Dice $dice
     * @param IntegerInterface $numberOfStandardRolls
     * @param DiceRollEvaluator $diceRollEvaluator
     * @param RollOn $bonusRollOn
     * @param RollOn $malusRollOn malus roll itself is responsible for negative or positive numbers
     * @throws \Granam\DiceRolls\Exceptions\InvalidDiceRange
     * @throws \Granam\DiceRolls\Exceptions\InvalidNumberOfRolls
     * @throws \Granam\DiceRolls\Exceptions\BonusAndMalusChanceConflict
     */
    public function __construct(
        Dice $dice,
        IntegerInterface $numberOfStandardRolls,
        DiceRollEvaluator $diceRollEvaluator,
        RollOn $bonusRollOn,
        RollOn $malusRollOn
    )
    {
        $this->checkDice($dice);
        $this->checkNumberOfStandardRolls($numberOfStandardRolls);
        $this->checkBonusAndMalusConflicts($dice, $bonusRollOn, $malusRollOn);
        $this->dice = $dice;
        $this->numberOfStandardRolls = $numberOfStandardRolls;
        $this->diceRollEvaluator = $diceRollEvaluator;
        $this->bonusRollOn = $bonusRollOn;
        $this->malusRollOn = $malusRollOn;
    }

    /**
     * @param Dice $dice
     * @throws \Granam\DiceRolls\Exceptions\InvalidDiceRange
     */
    private function checkDice(Dice $dice): void
    {
        if ($dice->getMinimum()->getValue() > $dice->getMaximum()->getValue()) {
            throw new Exceptions\InvalidDiceRange(
                'Dice minimum value has to be same or lesser than maximum value.'
                . " Got minimum {$dice->getMinimum()->getValue()} and maximum {$dice->getMaximum()->getValue()}."
            );
        }
    }

    /**
     * @param IntegerInterface $numberOfStandardRolls
     * @throws \Granam\DiceRolls\Exceptions\InvalidNumberOfRolls
     */
    private function checkNumberOfStandardRolls(IntegerInterface $numberOfStandardRolls): void
    {
        if ($numberOfStandardRolls->getValue() <= 0) {
            throw new Exceptions\InvalidNumberOfRolls(
                'Roll number has to be at least one, less rolls have no sense.'
                . " Got {$numberOfStandardRolls->getValue()}."
            );
        }
    }

    /**
     * @param Dice $dice
     * @param RollOn $bonusRollOn
     * @param RollOn $malusRollOn
     * @throws \Granam\DiceRolls\Exceptions\BonusAndMalusChanceConflict
     */
    private function checkBonusAndMalusConflicts(Dice $dice, RollOn $bonusRollOn, RollOn $malusRollOn): void
    {
        $bonusRollOnValues = $this->findRollOnValues($dice->getMinimum()->getValue(), $dice->getMaximum()->getValue(), $bonusRollOn);
        $malusRollOnValues = $this->findRollOnValues($dice->getMinimum()->getValue(), $dice->getMaximum()->getValue(), $malusRollOn);
        $conflicts = \array_intersect($bonusRollOnValues, $malusRollOnValues);
        if (\count($conflicts) > 0) {
            throw new Exceptions\BonusAndMalusChanceConflict('Bonus and malus rolls would happen on same values: ' . implode(',', $conflicts));
        }
    }

    /**
     * @param int $minimumRollValue
     * @param int $maximumRollValue
     * @param RollOn $rollOn
     * @return array|int[]
     */
    private function findRollOnValues(int $minimumRollValue, int $maximumRollValue, RollOn $rollOn): array
    {
        $rollOnValues = [];
        for ($rollValue = $minimumRollValue; $rollValue <= $maximumRollValue; $rollValue++) {
            if ($rollOn->shouldHappen($rollValue)) {
                $rollOnValues[] = $rollValue;
            }
        }

        return $rollOnValues;
    }

    /**
     * @param int $sequenceStartNumber = 1
     * @return Roll
     * @throws \Granam\DiceRolls\Exceptions\InvalidSequenceNumber
     */
    public function roll(int $sequenceStartNumber = 1): Roll
    {
        $standardDiceRolls = [];
        $sequenceStartNumber = $this->validateSequenceStart($sequenceStartNumber);
        $sequenceStopNumber = $this->numberOfStandardRolls->getValue() + $sequenceStartNumber - 1;
        for ($sequenceNumberValue = $sequenceStartNumber;
             $sequenceNumberValue <= $sequenceStopNumber; $sequenceNumberValue++
        ) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $sequenceNumber = new PositiveIntegerObject($sequenceNumberValue);
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $standardDiceRolls[] = $this->rollDice($sequenceNumber);
        }
        $standardRollsSum = $this->summarizeValues($this->extractRolledNumbers($standardDiceRolls));
        $nextSequenceStep = $sequenceStopNumber + 1;
        $bonusDiceRolls = $this->rollBonusDices($standardRollsSum, $nextSequenceStep);
        $malusDiceRolls = $this->rollMalusDices($standardRollsSum, $nextSequenceStep);

        return $this->createRoll($standardDiceRolls, $bonusDiceRolls, $malusDiceRolls);
    }

    /**
     * @param int $start
     * @return int
     * @throws \Granam\DiceRolls\Exceptions\InvalidSequenceNumber
     */
    private function validateSequenceStart(int $start): int
    {
        if ($start < 1) {
            throw new Exceptions\InvalidSequenceNumber(
                'Roll sequence start has to be at least 1, got ' . ValueDescriber::describe($start)
            );
        }

        return $start;
    }

    /**
     * @param int $standardRollsSum
     * @param int $rollSequenceStart
     * @return array|DiceRoll[]
     */
    private function rollBonusDices(int $standardRollsSum, int $rollSequenceStart): array
    {
        if (!$this->bonusRollOn->shouldHappen($standardRollsSum)) {
            return [];
        }

        return $this->bonusRollOn->rollDices($rollSequenceStart);
    }

    /**
     * @param int $standardRollsSum
     * @param int $rollSequenceStart
     * @return array|DiceRoll[]
     */
    private function rollMalusDices(int $standardRollsSum, int $rollSequenceStart): array
    {
        if (!$this->malusRollOn->shouldHappen($standardRollsSum)) {
            return [];
        }

        return $this->malusRollOn->rollDices($rollSequenceStart);
    }

    /**
     * @param array|DiceRoll[] $standardDiceRolls
     * @param array|DiceRoll[] $bonusDiceRolls
     * @param array|DiceRoll[] $malusDiceRolls
     * @return Roll
     */
    protected function createRoll(array $standardDiceRolls, array $bonusDiceRolls, array $malusDiceRolls): Roll
    {
        return new Roll($standardDiceRolls, $bonusDiceRolls, $malusDiceRolls);
    }

    /**
     * @param PositiveInteger $sequenceNumber
     * @return DiceRoll
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     */
    private function rollDice(PositiveInteger $sequenceNumber): DiceRoll
    {
        return new DiceRoll(
            $this->dice,
            new PositiveIntegerObject($this->rollNumber($this->dice)),
            $sequenceNumber,
            $this->diceRollEvaluator
        );
    }

    /**
     * @param Dice $dice
     * @return int
     */
    private function rollNumber(Dice $dice): int
    {
        try {
            return \random_int($dice->getMinimum()->getValue(), $dice->getMaximum()->getValue());
            // @codeCoverageIgnoreStart
        } catch (\Exception $exception) {
            return \rand($dice->getMinimum()->getValue(), $dice->getMaximum()->getValue());
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * @param array|DiceRoll[] $diceRolls
     * @return array|IntegerInterface[]
     */
    private function extractRolledNumbers(array $diceRolls): array
    {
        return array_merge(
            array_map(
                function (DiceRoll $diceRoll) {
                    return $diceRoll->getRolledNumber();
                },
                $diceRolls
            )
        );
    }

    /**
     * @param array|IntegerInterface[] $values
     * @return int
     */
    private function summarizeValues(array $values): int
    {
        return (int)array_sum(
            array_map(
                function (IntegerInterface $value) {
                    return $value->getValue();
                },
                $values
            )
        );
    }

    /**
     * @return Dice
     */
    public function getDice(): Dice
    {
        return $this->dice;
    }

    /**
     * @return IntegerInterface
     */
    public function getNumberOfStandardRolls(): IntegerInterface
    {
        return $this->numberOfStandardRolls;
    }

    /**
     * @return DiceRollEvaluator
     */
    public function getDiceRollEvaluator(): DiceRollEvaluator
    {
        return $this->diceRollEvaluator;
    }

    /**
     * @return RollOn|null
     */
    public function getBonusRollOn(): ? RollOn
    {
        return $this->bonusRollOn;
    }

    /**
     * @return RollOn|null
     */
    public function getMalusRollOn(): ? RollOn
    {
        return $this->malusRollOn;
    }

}