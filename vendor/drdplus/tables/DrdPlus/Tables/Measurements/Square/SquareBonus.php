<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\Measurements\Square;

use DrdPlus\Calculations\SumAndRound;
use DrdPlus\Codes\Units\DistanceUnitCode;
use DrdPlus\Codes\Units\SquareUnitCode;
use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
use DrdPlus\Tables\Measurements\Distance\DistanceTable;
use DrdPlus\Tables\Measurements\Partials\AbstractBonus;
use DrdPlus\Tables\Tables;

class SquareBonus extends AbstractBonus
{
    /**
     * @param int|\Granam\Integer\IntegerInterface $bonusValue
     * @param Tables $tables
     * @return SquareBonus
     * @throws \DrdPlus\Tables\Measurements\Partials\Exceptions\BonusRequiresInteger
     */
    public static function getIt($bonusValue, Tables $tables): SquareBonus
    {
        return new static($bonusValue, $tables->getDistanceTable());
    }

    /** @var DistanceTable */
    private $distanceTable;

    /**
     * @param \Granam\Integer\IntegerInterface|int $value
     * @param DistanceTable $distanceTable
     * @throws \DrdPlus\Tables\Measurements\Partials\Exceptions\BonusRequiresInteger
     */
    public function __construct($value, DistanceTable $distanceTable)
    {
        parent::__construct($value);
        $this->distanceTable = $distanceTable;
    }

    /**
     * @return Square
     * @throws \DrdPlus\Tables\Measurements\Square\Exceptions\SquareFromSquareBonusIsOutOfRange
     */
    public function getSquare(): Square
    {
        $squareBonusValue = $this->getValue();
        $squareSideBonusValue = SumAndRound::round($squareBonusValue / 2);
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $squareSideDistanceBonus = new DistanceBonus($squareSideBonusValue, $this->distanceTable);
        $squareSideDistance = $squareSideDistanceBonus->getDistance();
        $squareValue = $squareSideDistance->getValue() ** 2;

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new Square($squareValue, $this->getSquareUnitByDistanceUnit($squareSideDistance->getUnit()), $this->distanceTable);
    }

    /**
     * @param string $distanceUnit
     * @return string
     * @throws \DrdPlus\Tables\Measurements\Square\Exceptions\SquareFromSquareBonusIsOutOfRange
     */
    private function getSquareUnitByDistanceUnit(string $distanceUnit): string
    {
        switch ($distanceUnit) {
            case DistanceUnitCode::DECIMETER :
                return SquareUnitCode::SQUARE_DECIMETER;
            case DistanceUnitCode::METER :
                return SquareUnitCode::SQUARE_METER;
            case DistanceUnitCode::KILOMETER :
                return SquareUnitCode::SQUARE_KILOMETER;
            default :
                throw new Exceptions\SquareFromSquareBonusIsOutOfRange(
                    "Can not convert square bonus {$this->getValue()} to a square value as it is out of known values"
                );
        }
    }

}