<?php
declare(strict_types=1);

namespace Granam\DiceRolls\Templates\Dices;

use Granam\DiceRolls\Dice;
use Granam\Integer\IntegerInterface;
use Granam\Integer\IntegerObject;
use Granam\Strict\Object\StrictObject;
use Granam\Tools\ValueDescriber;

class Dices extends StrictObject implements Dice
{
    /** @var array|Dice[] */
    private $dices;
    /** @var IntegerObject */
    private $minimum;
    /** @var IntegerObject */
    private $maximum;

    /**
     * @param array|Dice[] $dices
     * @throws \LogicException
     */
    public function __construct(array $dices)
    {
        $this->checkDices($dices);
        $this->dices = $dices;
    }

    /**
     * @param array|Dice[] $dices
     * @throws \LogicException
     */
    private function checkDices(array $dices)
    {
        if (count($dices) === 0) {
            throw new \LogicException('No dice given.');
        }

        foreach ($dices as $dice) {
            if (!is_a($dice, Dice::class)) {
                throw new \LogicException(
                    'Given dices have to be DiceInterface, got ' . ValueDescriber::describe($dice)
                );
            }
        }
    }

    /**
     * @return IntegerInterface
     */
    public function getMinimum(): IntegerInterface
    {
        if ($this->minimum === null) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $this->minimum = $this->createMinimum();
        }

        return $this->minimum;
    }

    /**
     * @return IntegerObject
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     */
    private function createMinimum(): IntegerObject
    {
        return new IntegerObject(
            array_sum(
                array_map(
                    function (Dice $dice) {
                        return $dice->getMinimum()->getValue();
                    },
                    $this->dices
                )
            )
        );
    }

    /**
     * @return IntegerInterface
     */
    public function getMaximum(): IntegerInterface
    {
        if ($this->maximum === null) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $this->maximum = $this->createMaximum();
        }

        return $this->maximum;
    }

    /**
     * @return IntegerObject
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     */
    private function createMaximum(): IntegerObject
    {
        return new IntegerObject(
            array_sum(
                array_map(
                    function (Dice $dice) {
                        return $dice->getMaximum()->getValue();
                    },
                    $this->dices
                )
            )
        );
    }
}
