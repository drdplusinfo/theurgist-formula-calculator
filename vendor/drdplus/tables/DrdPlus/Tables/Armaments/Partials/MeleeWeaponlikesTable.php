<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

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