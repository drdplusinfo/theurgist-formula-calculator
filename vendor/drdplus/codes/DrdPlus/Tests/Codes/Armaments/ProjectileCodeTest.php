<?php declare(strict_types=1);

namespace DrdPlus\Tests\Codes\Armaments;

use DrdPlus\Codes\Armaments\ProjectileCode;

abstract class ProjectileCodeTest extends ArmamentCodeTest
{
    /**
     * @test
     */
    public function It_is_projectile()
    {
        $sutClass = self::getSutClass();
        $constants = (new \ReflectionClass($sutClass))->getConstants();
        $value = reset($constants);
        /** @var ProjectileCode $sut */
        $sut = $sutClass::getIt($value);

        self::assertTrue($sut->isProjectile());
        self::assertFalse($sut->isProtectiveArmament());
        self::assertFalse($sut->isWeaponlike());
    }

    /**
     * @test
     */
    abstract public function I_can_find_out_if_is_arrow();

    /**
     * @test
     */
    abstract public function I_can_find_out_if_is_dart();

    /**
     * @test
     */
    abstract public function I_can_find_out_if_is_sling_stone();
}