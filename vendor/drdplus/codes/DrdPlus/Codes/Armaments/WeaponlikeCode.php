<?php declare(strict_types=1); 

namespace DrdPlus\Codes\Armaments;

interface WeaponlikeCode extends ArmamentCode
{

    /**
     * If is not ranged weapon-like, is melee weapon-like (weapon or shield)
     *
     * @return bool
     */
    public function isMelee(): bool;

    /**
     * If is not melee weapon-like, is ranged-weaponlike.
     *
     * @return bool
     */
    public function isRanged(): bool;

    /**
     * Even shield can be used for (desperate) attack - that is why is weapon-like.
     *
     * @return bool
     */
    public function isShield(): bool;

    /**
     * Is it primarily a weapon, not a shield or something like that?
     *
     * @return bool
     */
    public function isWeapon(): bool;

    /**
     * If is range, can be shooting, throwing or a projectile
     *
     * @return bool
     */
    public function isShootingWeapon(): bool;

    /**
     * If is range, can be shooting, throwing or a projectile
     *
     * @return bool
     */
    public function isThrowingWeapon(): bool;

    /**
     * @return MeleeWeaponCode
     */
    public function convertToMeleeWeaponCodeEquivalent(): MeleeWeaponCode;

    /**
     * @return RangedWeaponCode
     */
    public function convertToRangedWeaponCodeEquivalent(): RangedWeaponCode;

    /**
     * @return bool
     */
    public function isUnarmed(): bool;

}