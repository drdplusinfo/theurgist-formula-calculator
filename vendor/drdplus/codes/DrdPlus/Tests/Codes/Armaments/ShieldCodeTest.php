<?php
namespace DrdPlus\Tests\Codes\Armaments;

use DrdPlus\Codes\Armaments\ProtectiveArmamentCode;
use DrdPlus\Codes\Armaments\ShieldCode;

class ShieldCodeTest extends WeaponlikeCodeTest implements ProtectiveArmamentCodeTest
{
    use MeleeWeaponlikeCodeTrait;

    /**
     * @param string $weaponlikeCode
     * @param string $interferingCodeClass
     * @return bool
     */
    protected function isSameCodeAllowedFor(string $weaponlikeCode, string $interferingCodeClass): bool
    {
        return false;
    }

    /**
     * @test
     */
    public function I_can_easily_find_out_if_is_protective_armament(): void
    {
        $shieldCode = ShieldCode::getIt(ShieldCode::MEDIUM_SHIELD);
        self::assertTrue($shieldCode->isProtectiveArmament());
        self::assertInstanceOf(ProtectiveArmamentCode::class, $shieldCode);
    }

    /**
     * @test
     */
    public function I_can_easily_find_out_if_is_ranged(): void
    {
        self::assertFalse(ShieldCode::getIt(ShieldCode::WITHOUT_SHIELD)->isRanged());
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function I_can_easily_find_out_if_is_melee(): void
    {
        $reflection = new \ReflectionClass(self::getSutClass());
        /** @var ShieldCode $sut */
        $sut = $reflection->newInstanceWithoutConstructor();
        self::assertTrue($sut->isMelee());
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function I_can_easily_find_out_if_is_weapon(): void
    {
        $reflection = new \ReflectionClass(self::getSutClass());
        /** @var ShieldCode $sut */
        $sut = $reflection->newInstanceWithoutConstructor();
        self::assertFalse($sut->isWeapon());
    }

    /**
     * @test
     */
    public function I_can_easily_find_out_if_is_shield(): void
    {
        self::assertTrue(ShieldCode::getIt(ShieldCode::BUCKLER)->isShield());
        self::assertFalse(ShieldCode::getIt(ShieldCode::BUCKLER)->isArmor());
    }

    /**
     * @test
     */
    public function I_can_easily_find_out_if_is_melee_weapon(): void
    {
        self::assertFalse(ShieldCode::getIt(ShieldCode::BUCKLER)->isMeleeWeapon());
    }

    /**
     * @test
     * @expectedException \DrdPlus\Codes\Armaments\Exceptions\CanNotBeConvertedToMeleeWeaponCode
     */
    public function I_can_not_convert_it_to_melee_weapon_code(): void
    {
        ShieldCode::getIt(ShieldCode::BUCKLER)->convertToMeleeWeaponCodeEquivalent();
    }

    /**
     * @test
     * @expectedException \DrdPlus\Codes\Armaments\Exceptions\CanNotBeConvertedToRangeWeaponCode
     */
    public function I_can_not_convert_it_to_range_weapon_code(): void
    {
        ShieldCode::getIt(ShieldCode::BUCKLER)->convertToRangedWeaponCodeEquivalent();
    }

    /**
     * @test
     */
    public function I_can_easily_find_out_it_is_not_projectile(): void
    {
        self::assertFalse(ShieldCode::getIt(ShieldCode::BUCKLER)->isProjectile());
    }

    /**
     * @test
     */
    public function I_can_easily_find_out_if_weapon_is_unarmed_in_fact(): void
    {
        self::assertTrue(ShieldCode::getIt(ShieldCode::WITHOUT_SHIELD)->isUnarmed());
        self::assertFalse(ShieldCode::getIt(ShieldCode::HEAVY_SHIELD)->isUnarmed());
    }

    /**
     * @test
     */
    public function I_can_get_it_with_default_value(): void
    {
        $sut = $this->findSut();
        self::assertSame(ShieldCode::WITHOUT_SHIELD, $sut->getValue(), 'Expected without shield as a default value');
    }

}