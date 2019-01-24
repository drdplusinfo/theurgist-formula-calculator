<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\Armaments\Weapons\Melee;

use DrdPlus\Tables\Armaments\Weapons\Melee\MeleeWeaponStrengthSanctionsTable;
use DrdPlus\Tests\Tables\Armaments\Partials\AbstractMeleeWeaponlikeStrengthSanctionsTableTest;

class MeleeWeaponStrengthSanctionsTableTest extends AbstractMeleeWeaponlikeStrengthSanctionsTableTest
{
    /**
     * @test
     */
    public function I_can_ask_it_if_can_use_weapon()
    {
        self::assertTrue((new MeleeWeaponStrengthSanctionsTable())->canUseIt(-999));
        self::assertTrue((new MeleeWeaponStrengthSanctionsTable())->canUseIt(10));
        self::assertFalse((new MeleeWeaponStrengthSanctionsTable())->canUseIt(11));
    }
}