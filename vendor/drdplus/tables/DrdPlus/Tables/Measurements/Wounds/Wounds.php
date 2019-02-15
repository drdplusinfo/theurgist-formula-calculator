<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Measurements\Wounds;

use DrdPlus\Tables\Measurements\Partials\AbstractMeasurementWithBonus;
use Granam\Float\Tools\Exceptions\PositiveFloatCanNotBeNegative;
use Granam\Integer\PositiveInteger;
use Granam\Integer\Tools\ToInteger;

class Wounds extends AbstractMeasurementWithBonus implements PositiveInteger
{
    public const WOUNDS = 'wounds';

    /** @var WoundsTable */
    private $woundsTable;

    /**
     * @param \Granam\Integer\IntegerInterface|int $value
     * @param WoundsTable $woundsTable
     * @throws \DrdPlus\Tables\Measurements\Wounds\Exceptions\WoundsCanNotBeNegative
     * @throws \Granam\Float\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Float\Tools\Exceptions\ValueLostOnCast
     */
    public function __construct($value, WoundsTable $woundsTable)
    {
        try {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            parent::__construct($value, self::WOUNDS);
        } catch (PositiveFloatCanNotBeNegative $exception) {
            throw new Exceptions\WoundsCanNotBeNegative(
                'Wounds has to be positive integer with least zero as \'healed\', given ' . \var_export($value, true)
            );
        }
        $this->woundsTable = $woundsTable;
    }

    /**
     * @param mixed $value
     * @return int
     * @throws \Granam\Integer\Tools\Exceptions\PositiveIntegerCanNotBeNegative
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     */
    protected function normalizeValue($value): int
    {
        return ToInteger::toPositiveInteger($value);
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        // turning float to integer (without value lost)
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return ToInteger::toInteger(parent::getValue());
    }

    /**
     * @return array|string[]
     */
    public function getPossibleUnits(): array
    {
        return [self::WOUNDS];
    }

    /**
     * @return WoundsBonus
     */
    public function getBonus(): WoundsBonus
    {
        return $this->woundsTable->toBonus($this);
    }
}