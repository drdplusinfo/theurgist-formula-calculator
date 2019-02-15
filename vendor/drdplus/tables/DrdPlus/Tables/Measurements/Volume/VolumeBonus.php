<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Measurements\Volume;

use DrdPlus\Calculations\SumAndRound;
use DrdPlus\Codes\Units\DistanceUnitCode;
use DrdPlus\Codes\Units\VolumeUnitCode;
use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
use DrdPlus\Tables\Measurements\Distance\DistanceTable;
use DrdPlus\Tables\Measurements\Partials\AbstractBonus;
use DrdPlus\Tables\Tables;

class VolumeBonus extends AbstractBonus
{
    /**
     * @param int|\Granam\Integer\IntegerInterface $bonusValue
     * @param Tables $tables
     * @return VolumeBonus
     * @throws \DrdPlus\Tables\Measurements\Partials\Exceptions\BonusRequiresInteger
     */
    public static function getIt($bonusValue, Tables $tables): VolumeBonus
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
     * @return Volume
     * @throws \DrdPlus\Tables\Measurements\Volume\Exceptions\VolumeFromVolumeBonusIsOutOfRange
     */
    public function getVolume(): Volume
    {
        $distanceBonusValue = SumAndRound::round($this->getValue() / 3);
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $distanceBonus = new DistanceBonus($distanceBonusValue, $this->distanceTable);
        $cubeSideDistance = $distanceBonus->getDistance();
        $volumeValue = $cubeSideDistance->getValue() ** 3;

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new Volume(
            $volumeValue,
            $this->getVolumeUnitByDistanceUnit($cubeSideDistance->getUnit()),
            $this->distanceTable
        );
    }

    /**
     * @param string $distanceUnit
     * @return string
     * @throws \DrdPlus\Tables\Measurements\Volume\Exceptions\VolumeFromVolumeBonusIsOutOfRange
     */
    private function getVolumeUnitByDistanceUnit(string $distanceUnit): string
    {
        switch ($distanceUnit) {
            case DistanceUnitCode::DECIMETER :
                return VolumeUnitCode::LITER;
            case DistanceUnitCode::METER :
                return VolumeUnitCode::CUBIC_METER;
            case DistanceUnitCode::KILOMETER :
                return VolumeUnitCode::CUBIC_KILOMETER;
            default :
                throw new Exceptions\VolumeFromVolumeBonusIsOutOfRange(
                    "Can not convert volume bonus {$this->getValue()} to a volume as it is out of known values"
                );
        }
    }
}