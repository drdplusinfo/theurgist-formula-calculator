<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\Riding;

use DrdPlus\Codes\Transport\RidingAnimalMovementCode;
use DrdPlus\Tables\Measurements\Wounds\WoundsBonus;
use DrdPlus\Tables\Measurements\Wounds\WoundsTable;
use DrdPlus\Tables\Partials\AbstractFileTable;

/**
 * See PPH page 122 right column bottom, @link https://pph.drdplus.info/#tabulka_zraneni_pri_padu_z_kone
 */
class WoundsOnFallFromHorseTable extends AbstractFileTable
{
    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/wounds_on_fall_from_horse.csv';
    }

    /**
     * @return array|string[]
     */
    protected function getRowsHeader(): array
    {
        return ['activity'];
    }

    public const WOUNDS_MODIFICATION = 'wounds_modification';
    public const ADDITIONAL = 'additional';

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [self::WOUNDS_MODIFICATION => self::INTEGER, self::ADDITIONAL => self::BOOLEAN];
    }

    public const STILL = RidingAnimalMovementCode::STILL;
    public const GAIT = RidingAnimalMovementCode::GAIT;
    public const TROT = RidingAnimalMovementCode::TROT;
    public const CANTER = RidingAnimalMovementCode::CANTER;
    public const GALLOP = RidingAnimalMovementCode::GALLOP;
    public const JUMPING = RidingAnimalMovementCode::JUMPING;

    /**
     * @param RidingAnimalMovementCode $ridingAnimalMovementCode
     * @param bool $jumping
     * @param WoundsTable $woundsTable
     * @return WoundsBonus
     */
    public function getWoundsAdditionOnFallFromHorse(
        RidingAnimalMovementCode $ridingAnimalMovementCode,
        bool $jumping,
        WoundsTable $woundsTable
    ): WoundsBonus
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return new WoundsBonus(
            $this->getValue([$ridingAnimalMovementCode->getValue()], self::WOUNDS_MODIFICATION)
            + ($jumping
                ? $this->getValue([RidingAnimalMovementCode::JUMPING], self::WOUNDS_MODIFICATION)
                : 0
            ),
            $woundsTable
        );
    }

}