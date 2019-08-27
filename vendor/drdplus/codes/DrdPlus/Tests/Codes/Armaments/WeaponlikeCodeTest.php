<?php declare(strict_types=1);

namespace DrdPlus\Tests\Codes\Armaments;

use DrdPlus\Codes\Armaments\WeaponlikeCode;

abstract class WeaponlikeCodeTest extends ArmamentCodeTest
{
    /**
     * @test
     */
    public function It_is_weaponlike_code()
    {
        $reflection = new \ReflectionClass(self::getSutClass());
        /** @var WeaponlikeCode $sut */
        $sut = $reflection->newInstanceWithoutConstructor();
        self::assertInstanceOf(WeaponlikeCode::class, $sut);
        self::assertTrue($sut->isWeaponlike());
    }

    private static $weaponlikeCodes = [];

    /**
     * This is important for weaponlike code as an enum - to be easily determined by @see CodeType
     *
     * @test
     */
    public function Has_unique_codes_across_all_weaponlikes()
    {
        $sutClass = self::getSutClass();
        $reflection = new \ReflectionClass($sutClass);
        $sameCodes = [];
        foreach (self::$weaponlikeCodes as $otherCodeClass => $weaponlikeCodes) {
            // looking for same values
            foreach (array_intersect($weaponlikeCodes, $reflection->getConstants()) as $sameCode) {
                if (!$this->isSameCodeAllowedFor($sameCode, $otherCodeClass)) {
                    $sameCodes[] = "$sameCode (from codes $sutClass and $otherCodeClass)";
                }
            }
        }
        if (count($sameCodes) > 0) {
            self::fail('Weaponlike codes have same values: ' . implode(',', $sameCodes));
        }
        self::$weaponlikeCodes[$sutClass] = $reflection->getConstants();
    }

    /**
     * @param string $weaponlikeCode
     * @param string $interferingCodeClass
     * @return bool
     */
    abstract protected function isSameCodeAllowedFor(string $weaponlikeCode, string $interferingCodeClass): bool;

    /**
     * @test
     */
    abstract public function I_can_easily_find_out_if_is_weapon();

    /**
     * @test
     */
    abstract public function I_can_easily_find_out_if_is_ranged();

    /**
     * @test
     */
    abstract public function I_can_easily_find_out_if_is_shield();

    /**
     * @test
     */
    abstract public function I_can_easily_find_out_if_is_melee();

    /**
     * @test
     */
    abstract public function I_can_easily_find_out_it_is_not_projectile();

    /**
     * @test
     */
    abstract public function I_can_easily_find_out_if_weapon_is_unarmed_in_fact();
}