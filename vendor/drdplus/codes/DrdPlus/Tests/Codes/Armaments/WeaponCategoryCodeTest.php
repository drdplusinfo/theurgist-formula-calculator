<?php
namespace DrdPlus\Tests\Codes\Armaments;

use DrdPlus\Codes\Armaments\WeaponCategoryCode;
use DrdPlus\Tests\Codes\Partials\TranslatableCodeTest;

class WeaponCategoryCodeTest extends TranslatableCodeTest
{
    /**
     * @test
     */
    public function I_can_get_melee_weapon_category_codes_at_once()
    {
        self::assertSame(
            $codes = [
                'axes',
                'knives_and_daggers',
                'maces_and_clubs',
                'morningstars_and_morgensterns',
                'sabers_and_bowie_knives',
                'staffs_and_spears',
                'swords',
                'voulges_and_tridents',
                'unarmed',
            ],
            WeaponCategoryCode::getMeleeWeaponCategoryValues()
        );
        foreach ($codes as $code) {
            $meleeCategory = WeaponCategoryCode::getIt($code);
            self::assertTrue($meleeCategory->isMeleeWeaponCategory());
            self::assertFalse($meleeCategory->isRangedWeaponCategory());
            self::assertFalse($meleeCategory->isProjectileCategory());
        }
    }

    /**
     * @test
     */
    public function I_can_get_ranged_weapon_category_codes()
    {
        self::assertSame(
            $codes = ['bows', 'crossbows', 'throwing_weapons'],
            WeaponCategoryCode::getRangedWeaponCategoryValues()
        );
        foreach ($codes as $code) {
            $rangedCategory = WeaponCategoryCode::getIt($code);
            self::assertTrue($rangedCategory->isRangedWeaponCategory());
            self::assertFalse($rangedCategory->isMeleeWeaponCategory());
            self::assertFalse($rangedCategory->isProjectileCategory());
        }
    }

    /**
     * @test
     */
    public function I_can_get_projectile_category_codes()
    {
        self::assertSame(
            $codes = ['arrows', 'darts', 'sling_stones'],
            WeaponCategoryCode::getProjectileCategoryValues()
        );
        foreach ($codes as $code) {
            $projectileCategory = WeaponCategoryCode::getIt($code);
            self::assertTrue($projectileCategory->isProjectileCategory());
            self::assertFalse($projectileCategory->isMeleeWeaponCategory());
            self::assertFalse($projectileCategory->isRangedWeaponCategory());
        }
    }

}