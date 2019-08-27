<?php declare(strict_types=1);

namespace DrdPlus\Codes\Armaments;

interface MeleeWeaponlikeCode extends WeaponlikeCode
{
    /**
     * It can be just a melee, or as melee weapon-like usable in times of need and despair, or it is pure MELEE WEAPON.
     *
     * @return bool
     */
    public function isMeleeWeapon(): bool;
}