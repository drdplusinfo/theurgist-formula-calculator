<?php declare(strict_types=1); 

namespace DrdPlus\Codes\Armaments;

use DrdPlus\Codes\Partials\FileBasedTranslatableExtendableCode;

abstract class ProjectileCode extends FileBasedTranslatableExtendableCode implements ArmamentCode
{
    /**
     * @return bool
     */
    public function isProtectiveArmament(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isWeaponlike(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    final public function isProjectile(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    abstract public function isArrow(): bool;

    /**
     * @return bool
     */
    abstract public function isDart(): bool;

    /**
     * @return bool
     */
    abstract public function isSlingStone(): bool;
}