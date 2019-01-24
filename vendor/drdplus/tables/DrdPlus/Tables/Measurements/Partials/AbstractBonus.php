<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\Measurements\Partials;

use DrdPlus\Tables\Measurements\Bonus;
use Granam\Integer\IntegerInterface;
use Granam\Integer\Tools\ToInteger;
use Granam\Strict\Object\StrictObject;

abstract class AbstractBonus extends StrictObject implements Bonus
{
    /**
     * @var int
     */
    private $value;

    /**
     * @param int|IntegerInterface $value
     * @throws \DrdPlus\Tables\Measurements\Partials\Exceptions\BonusRequiresInteger
     */
    protected function __construct($value)
    {
        try {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $this->value = ToInteger::toInteger($value);
        } catch (\Granam\Integer\Tools\Exceptions\WrongParameterType $exception) {
            throw new Exceptions\BonusRequiresInteger($exception->getMessage());
        }
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getValue();
    }

}