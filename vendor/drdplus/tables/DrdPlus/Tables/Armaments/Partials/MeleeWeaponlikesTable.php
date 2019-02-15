<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Armaments\Partials;

use DrdPlus\Codes\Armaments\MeleeWeaponCode;

interface MeleeWeaponlikesTable extends WeaponlikeTable
{
    public const LENGTH = 'length';

    /**
     * @param string|MeleeWeaponCode $weaponlikeCode
     * @return int
     */
    public function getLengthOf($weaponlikeCode): int;

}