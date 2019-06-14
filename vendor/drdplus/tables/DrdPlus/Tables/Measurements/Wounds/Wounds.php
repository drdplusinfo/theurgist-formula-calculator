<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Measurements\Wounds;

use DrdPlus\Tables\Measurements\Partials\AbstractMeasurementWithBonus;
use Granam\Integer\PositiveInteger;
use Granam\Integer\Tools\Exceptions\PositiveIntegerCanNotBeNegative;
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
            parent::__construct($value, self::WOUNDS);
        } catch (PositiveIntegerCanNotBeNegative $exception) {
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

    public function getValue(): int
    {
        // turning float to integer (without value lost)
        return ToInteger::toInteger(parent::getValue());
    }

    /**
     * @return array|string[]
     */
    public function getPossibleUnits(): array
    {
        return [self::WOUNDS];
    }

    public function getBonus(): WoundsBonus
    {
        return $this->woundsTable->toBonus($this);
    }
}