<?php
declare(strict_types=1); 

namespace DrdPlus\Codes\Armaments;

use DrdPlus\Codes\Partials\TranslatableExtendableCode;

abstract class WeaponCode extends TranslatableExtendableCode implements WeaponlikeCode
{
    /** @return bool */
    public function isProtectiveArmament(): bool
    {
        return false;
    }

    /** @return bool */
    final public function isWeaponlike(): bool
    {
        return true;
    }

    /** @return bool */
    final public function isWeapon(): bool
    {
        return true;
    }

    /** @return bool */
    public function isShield(): bool
    {
        return false;
    }

    /** @return bool */
    public function isProjectile(): bool
    {
        return false;
    }

}