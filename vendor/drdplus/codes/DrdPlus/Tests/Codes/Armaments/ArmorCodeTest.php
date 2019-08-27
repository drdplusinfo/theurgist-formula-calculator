<?php declare(strict_types=1);

namespace DrdPlus\Tests\Codes\Armaments;

use DrdPlus\Codes\Armaments\ArmorCode;
use DrdPlus\Codes\Armaments\ProtectiveArmamentCode;

abstract class ArmorCodeTest extends ArmamentCodeTest implements ProtectiveArmamentCodeTest
{
    /**
     * @test
     */
    abstract public function I_can_ask_it_if_is_helm_or_body_armor();

    /**
     * @test
     */
    public function I_can_easily_find_out_if_is_protective_armament()
    {
        /** @var ArmorCode $sut */
        $sut = $this->getSut();
        self::assertTrue($sut->isProtectiveArmament());
        self::assertInstanceOf(ProtectiveArmamentCode::class, $sut);
    }

    /**
     * @test
     */
    public function It_is_armor_code()
    {
        /** @var ArmorCode $sut */
        $sut = $this->getSut();
        self::assertInstanceOf(ArmorCode::class, $sut);
        self::assertTrue($sut->isArmor());
        self::assertFalse($sut->isShield());
        self::assertFalse($sut->isWeaponlike());
        self::assertFalse($sut->isProjectile());
    }
}