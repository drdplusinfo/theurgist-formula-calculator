<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Measurements\Amount;

use DrdPlus\Tables\Measurements\Bonus;
use DrdPlus\Tables\Measurements\Partials\AbstractMeasurementWithBonus;
use DrdPlus\Tables\Tables;
use Granam\Integer\IntegerInterface;
use Granam\Integer\Tools\ToInteger;
use Granam\String\StringInterface;

/**
 * @method int getValue
 */
class Amount extends AbstractMeasurementWithBonus
{
    public const AMOUNT = 'amount';

    /**
     * @param int|IntegerInterface $value
     * @param Tables $tables
     * @return Amount
     */
    public static function getIt($value, Tables $tables): Amount
    {
        return new static($value, static::AMOUNT, $tables->getAmountTable());
    }

    /** @var AmountTable */
    private $amountTable;

    /**
     * @param int|IntegerInterface $value
     * @param string|StringInterface $unit
     * @param AmountTable $amountTable
     * @throws \DrdPlus\Tables\Measurements\Exceptions\UnknownUnit
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    public function __construct($value, $unit, AmountTable $amountTable)
    {
        $this->amountTable = $amountTable;
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        parent::__construct($value, $unit);
    }

    /**
     * @param mixed $value
     * @return int
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     */
    protected function normalizeValue($value): int
    {
        return ToInteger::toInteger($value);
    }

    /**
     * @return array|string[]
     */
    public function getPossibleUnits(): array
    {
        return [self::AMOUNT];
    }

    /**
     * @return AmountBonus|Bonus
     */
    public function getBonus(): Bonus
    {
        return $this->amountTable->toBonus($this);
    }

}