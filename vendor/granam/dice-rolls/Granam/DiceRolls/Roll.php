<?php
declare(strict_types=1);

namespace Granam\DiceRolls;

use Granam\Integer\IntegerInterface;
use Granam\Strict\Object\StrictObject;

class Roll extends StrictObject implements IntegerInterface
{

    /**
     * Standard dice rolls, without bonus and malus rolls
     *
     * @var array|DiceRoll[]
     */
    private $standardDiceRolls;
    /** @var array|DiceRoll[] */
    private $bonusDiceRolls;
    /** @var array|DiceRoll[] */
    private $malusDiceRolls;
    /** @var int|null */
    private $value;

    /**
     * @param array|DiceRoll[] $standardDiceRolls
     * @param array|DiceRoll[] $bonusDiceRolls = array()
     * @param array|DiceRoll[] $malusDiceRolls = array()
     */
    public function __construct(array $standardDiceRolls, array $bonusDiceRolls = [], array $malusDiceRolls = [])
    {
        $this->standardDiceRolls = $standardDiceRolls;
        $this->bonusDiceRolls = $bonusDiceRolls;
        $this->malusDiceRolls = $malusDiceRolls;
    }

    /**
     * @return array|DiceRoll[]
     */
    public function getDiceRolls(): array
    {
        return array_merge($this->standardDiceRolls, $this->bonusDiceRolls, $this->malusDiceRolls);
    }

    /**
     * @return array|IntegerInterface[]
     */
    public function getRolledNumbers(): array
    {
        return array_merge(
            array_map(
                function (DiceRoll $diceRoll) {
                    return $diceRoll->getRolledNumber();
                },
                $this->getDiceRolls()
            )
        );
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        if ($this->value === null) {
            $this->value = (int)array_sum($this->getRolledValues());
        }

        return $this->value;
    }

    /**
     * @return array|int[]
     */
    private function getRolledValues(): array
    {
        return array_merge(
            array_map(
                function (DiceRoll $diceRoll) {
                    return $diceRoll->getValue();
                },
                $this->getDiceRolls()
            )
        );
    }

    public function __toString()
    {
        return (string)$this->getValue();
    }

    /**
     * @return array|DiceRoll[]
     */
    public function getStandardDiceRolls(): array
    {
        return $this->standardDiceRolls;
    }

    /**
     * @return array|DiceRoll[]
     */
    public function getBonusDiceRolls(): array
    {
        return $this->bonusDiceRolls;
    }

    /**
     * @return array|DiceRoll[]
     */
    public function getMalusDiceRolls(): array
    {
        return $this->malusDiceRolls;
    }
}