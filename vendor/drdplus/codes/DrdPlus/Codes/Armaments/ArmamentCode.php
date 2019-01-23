<?php
declare(strict_types=1); 

namespace DrdPlus\Codes\Armaments;

use DrdPlus\Codes\Code;

interface ArmamentCode extends Code
{
    /**
     * Can be used for passive defense (like cover as shield or just lowering wounds as an armor) ?
     *
     * @return bool
     */
    public function isProtectiveArmament(): bool;

    /**
     * Can be used for attack directly (projectiles can not) ?
     *
     * @return bool
     */
    public function isWeaponlike(): bool;

    /**
     * Can not be used as a solo weapon, therefore has to be used together with another weapon like a bow ?
     *
     * @return bool
     */
    public function isProjectile(): bool;

}