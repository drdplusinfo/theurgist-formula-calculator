<?php declare(strict_types=1);

namespace DrdPlus\Tests\Codes\Armaments;

use DrdPlus\Codes\Armaments\MeleeWeaponlikeCode;

/**
 * @method MeleeWeaponlikeCode getSut
 */
trait MeleeWeaponlikeCodeTrait
{
    /**
     * @test
     */
    public function It_is_melee_weaponlike_code()
    {
        $sut = $this->getSut();
        self::assertInstanceOf(MeleeWeaponlikeCode::class, $sut);
    }

    /**
     * @test
     */
    public function It_is_not_range_nor_shooting_nor_throwing_weapon_nor_projectile_code()
    {
        $sut = $this->getSut();
        self::assertFalse($sut->isRanged());
        self::assertFalse($sut->isShootingWeapon());
        self::assertFalse($sut->isThrowingWeapon());
        self::assertFalse($sut->isProjectile());
    }

    /**
     * @test
     */
    abstract public function I_can_easily_find_out_if_is_shield();

    /**
     * @test
     */
    abstract public function I_can_easily_find_out_if_is_melee_weapon();

}