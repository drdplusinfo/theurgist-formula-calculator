<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\Armaments\Partials;

use DrdPlus\Codes\Armaments\WeaponlikeCode;

interface WeaponlikeTable extends WoundingArmamentsTable, HeavyBearablesTable
{
    public const COVER = 'cover';

    /**
     * @param string|WeaponlikeCode $weaponlikeCode
     * @return int
     */
    public function getCoverOf($weaponlikeCode): int;

    public const TWO_HANDED_ONLY = 'two_handed_only';

    /**
     * @param string|WeaponlikeCode $itemCode
     * @return bool
     */
    public function getTwoHandedOnlyOf($itemCode): bool;
}