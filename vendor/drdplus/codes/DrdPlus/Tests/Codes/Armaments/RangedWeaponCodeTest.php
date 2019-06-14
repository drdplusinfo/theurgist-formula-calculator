<?php
namespace DrdPlus\Tests\Codes\Armaments;

use DrdPlus\Codes\Armaments\Exceptions\CanNotBeConvertedToMeleeWeaponCode;
use DrdPlus\Codes\Armaments\MeleeWeaponCode;
use DrdPlus\Codes\Armaments\RangedWeaponCode;
use DrdPlus\Codes\Armaments\WeaponCategoryCode;
use DrdPlus\Codes\Partials\Exceptions\UnknownValueForCode;
use Granam\String\StringTools;

class RangedWeaponCodeTest extends WeaponCodeTest
{

    /**
     * @test
     */
    public function I_can_easily_composer_method_to_get_weapons_of_same_category()
    {
        foreach (WeaponCategoryCode::getRangedWeaponCategoryValues() as $rangedWeaponCategoryValue) {
            $getRangedWeaponOfCategory = StringTools::assembleGetterForName($rangedWeaponCategoryValue . 'Values');
            self::assertTrue(method_exists(self::getSutClass(), $getRangedWeaponOfCategory));
        }
    }

    /**
     * @param string $weaponlikeCode
     * @param string $interferingCodeClass
     * @return bool
     */
    protected function isSameCodeAllowedFor(string $weaponlikeCode, string $interferingCodeClass): bool
    {
        try {
            return is_a(RangedWeaponCode::getIt($weaponlikeCode)->convertToMeleeWeaponCodeEquivalent(), $interferingCodeClass);
        } catch (CanNotBeConvertedToMeleeWeaponCode $canNotBeConvertedToMeleeWeaponCode) {
            return false;
        } catch (UnknownValueForCode $unknownValueForCode) {
            return false;
        }
    }

    /**
     * @test
     */
    public function I_can_easily_find_out_if_is_melee()
    {
        $rangedWeaponCode = RangedWeaponCode::getIt(RangedWeaponCode::getPossibleValues()[0]);
        self::assertFalse($rangedWeaponCode->isMelee());
    }

    /**
     * @test
     */
    public function I_can_get_bow_codes()
    {
        self::assertSame(
            [
                'short_bow',
                'long_bow',
                'short_composite_bow',
                'long_composite_bow',
                'power_bow',
            ],
            RangedWeaponCode::getBowsValues()
        );
    }

    /**
     * @test
     */
    public function I_can_get_crossbow_codes()
    {
        self::assertSame(
            [
                'minicrossbow',
                'light_crossbow',
                'military_crossbow',
                'heavy_crossbow',
            ],
            RangedWeaponCode::getCrossbowsValues()
        );
    }

    /**
     * @test
     */
    public function I_can_get_throwing_weapon_codes()
    {
        self::assertSame(
            [
                'sand',
                'rock',
                'throwing_dagger',
                'light_throwing_axe',
                'war_throwing_axe',
                'throwing_hammer',
                'shuriken',
                'spear',
                'javelin',
                'sling',
            ],
            RangedWeaponCode::getThrowingWeaponsValues()
        );
    }

    /**
     * @test
     */
    public function I_can_get_all_codes_at_once_or_by_same_named_constant()
    {
        self::assertSame(
            $expectedValues = [
                // throwing weapons
                'sand',
                'rock',
                'throwing_dagger',
                'light_throwing_axe',
                'war_throwing_axe',
                'throwing_hammer',
                'shuriken',
                'spear',
                'javelin',
                'sling',
                // bows
                'short_bow',
                'long_bow',
                'short_composite_bow',
                'long_composite_bow',
                'power_bow',
                // crossbows
                'minicrossbow',
                'light_crossbow',
                'military_crossbow',
                'heavy_crossbow',
            ],
            RangedWeaponCode::getPossibleValues(),
            'There are ' . (
            count($missingOrDifferent = array_diff_assoc($expectedValues, RangedWeaponCode::getPossibleValues())) > 0
                ? 'missing values or different keys in given: ' . var_export($missingOrDifferent, true)
                : 'superfluous values or different keys in given: ' . var_export(array_diff_assoc(RangedWeaponCode::getPossibleValues(), $expectedValues), true)
            )
        );
    }

    /**
     * @test
     */
    public function I_can_ask_code_if_is_specific_weapon_type()
    {
        $questions = [
            'isBow', 'isCrossbow', 'isThrowingWeapon',
        ];
        foreach (RangedWeaponCode::getBowsValues() as $codeValue) {
            $code = RangedWeaponCode::getIt($codeValue);
            self::assertTrue($code->isRanged());
            self::assertFalse($code->isMelee());
            self::assertTrue($code->isBow());
            foreach ($questions as $question) {
                if ($question !== 'isBow') {
                    self::assertFalse($code->$question());
                }
            }
        }
        foreach (RangedWeaponCode::getCrossbowsValues() as $codeValue) {
            $code = RangedWeaponCode::getIt($codeValue);
            self::assertTrue($code->isRanged());
            self::assertFalse($code->isMelee());
            self::assertTrue($code->isCrossbow());
            foreach ($questions as $question) {
                if ($question !== 'isCrossbow') {
                    self::assertFalse($code->$question());
                }
            }
        }
        foreach (RangedWeaponCode::getThrowingWeaponsValues() as $codeValue) {
            $code = RangedWeaponCode::getIt($codeValue);
            self::assertTrue($code->isRanged());
            self::assertFalse($code->isMelee());
            foreach ($questions as $question) {
                if ($question !== 'isThrowingWeapon') {
                    if ($question !== 'isStaffOrSpear' || $codeValue !== RangedWeaponCode::SPEAR) {
                        self::assertFalse($code->$question());
                    } else {
                        self::assertTrue($code->$question(), "{$codeValue} should be {$question}");
                    }
                }
            }
        }
    }

    /**
     * @test
     */
    public function I_can_convert_spear_to_melee_weapon_code()
    {
        $rangeSpear = RangedWeaponCode::getIt(RangedWeaponCode::SPEAR);
        self::assertInstanceOf(RangedWeaponCode::class, $rangeSpear);
        self::assertSame($rangeSpear, $rangeSpear->convertToRangedWeaponCodeEquivalent());
        $meleeSpear = $rangeSpear->convertToMeleeWeaponCodeEquivalent();
        self::assertNotSame($rangeSpear, $meleeSpear);
        self::assertInstanceOf(MeleeWeaponCode::class, $meleeSpear);
        self::assertSame(MeleeWeaponCode::getIt(MeleeWeaponCode::SPEAR), $meleeSpear);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Codes\Armaments\Exceptions\CanNotBeConvertedToMeleeWeaponCode
     * @expectedExceptionMessageRegExp ~minicrossbow~
     */
    public function I_can_not_convert_anything_to_melee_weapon_code()
    {
        $rangeWeapon = RangedWeaponCode::getIt(RangedWeaponCode::MINICROSSBOW);
        self::assertFalse($rangeWeapon->isMelee());
        $rangeWeapon->convertToMeleeWeaponCodeEquivalent();
    }

    /**
     * @test
     * @dataProvider provideCodeAndUsage
     * @param $rangeWeaponCodeValue
     * @param bool $isThrowing
     * @param bool $isShooting
     */
    public function I_can_distinguish_throwing_and_shooting_weapon(
        $rangeWeaponCodeValue,
        $isThrowing,
        $isShooting
    )
    {
        $rangeWeaponCode = RangedWeaponCode::getIt($rangeWeaponCodeValue);
        self::assertFalse($rangeWeaponCode->isProjectile());
        self::assertSame($isThrowing, $rangeWeaponCode->isThrowingWeapon());
        self::assertSame($isShooting, $rangeWeaponCode->isShootingWeapon());
        self::assertFalse($rangeWeaponCode->isMelee());
    }

    public function provideCodeAndUsage(): array
    {
        return [
            // throwing weapons
            ['sand', true, false, false],
            ['rock', true, false, false],
            ['throwing_dagger', true, false, false],
            ['light_throwing_axe', true, false, false],
            ['war_throwing_axe', true, false, false],
            ['throwing_hammer', true, false, false],
            ['shuriken', true, false, false],
            ['spear', true, false, false],
            ['javelin', true, false, false],
            ['sling', true, false, false],
            // bows
            ['short_bow', false, true, false],
            ['long_bow', false, true, false],
            ['short_composite_bow', false, true, false],
            ['long_composite_bow', false, true, false],
            ['power_bow', false, true, false],
            // crossbows
            ['minicrossbow', false, true, false],
            ['light_crossbow', false, true, false],
            ['military_crossbow', false, true, false],
            ['heavy_crossbow', false, true, false],
        ];
    }

    /**
     * @test
     */
    public function I_can_easily_find_out_if_weapon_is_unarmed_in_fact()
    {
        self::assertFalse(RangedWeaponCode::getIt(RangedWeaponCode::LONG_COMPOSITE_BOW)->isUnarmed());
    }

    /**
     * @test
     */
    public function I_can_easily_find_out_if_is_ranged()
    {
        self::assertTrue(RangedWeaponCode::getIt(RangedWeaponCode::MILITARY_CROSSBOW)->isRanged());
    }

    protected function getWeaponCategoryValues(): array
    {
        return WeaponCategoryCode::getRangedWeaponCategoryValues();
    }

    /**
     * @test
     * @runInSeparateProcess
     * @expectedException \DrdPlus\Codes\Armaments\Exceptions\InvalidWeaponCategoryForNewRangedWeaponCode
     * @expectedExceptionMessageRegExp ~voulge~
     */
    public function I_can_not_add_new_ranged_weapon_code_with_not_melee_category()
    {
        $meleeCategory = WeaponCategoryCode::getIt(WeaponCategoryCode::VOULGES_AND_TRIDENTS);
        self::assertFalse($meleeCategory->isRangedWeaponCategory());
        RangedWeaponCode::addNewRangedWeaponCode('foo', $meleeCategory, []);
    }

    /**
     * @test
     * @runInSeparateProcess
     * @expectedException \DrdPlus\Codes\Armaments\Exceptions\RangedWeaponIsAlreadyInDifferentWeaponCategory
     * @throws \ReflectionException
     */
    public function I_can_not_extended_it_by_same_code_but_different_category()
    {
        $reflectionClass = new \ReflectionClass(RangedWeaponCode::class);
        $translations = $reflectionClass->getProperty('translations');
        $translations->setAccessible(true);
        // to reset already initialized translations and force them to be loaded again
        $translations->setValue(null);
        $translations->setAccessible(false);
        self::assertNotContains('corge', RangedWeaponCode::getPossibleValues());
        self::assertTrue(
            RangedWeaponCode::addNewRangedWeaponCode('corge', $someCategory = $this->getRandomWeaponCategoryCode(), [])
        );
        $differentCategory = $this->getRandomWeaponCategoryCode($someCategory);
        self::assertNotEquals($someCategory, $differentCategory);
        RangedWeaponCode::addNewRangedWeaponCode('corge', $differentCategory, []);
    }

    /**
     * @test
     */
    public function I_can_get_it_with_default_value()
    {
        $sut = $this->findSut();
        self::assertSame(RangedWeaponCode::SAND, $sut->getValue(), 'Expected sand as a harmless default value');
    }
}