<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Armaments\Weapons\Ranged;

use DrdPlus\Codes\Armaments\RangedWeaponCode;
use DrdPlus\Codes\Armaments\WeaponCategoryCode;
use DrdPlus\Codes\Body\PhysicalWoundTypeCode;
use DrdPlus\BaseProperties\Strength;
use DrdPlus\Tables\Armaments\Weapons\Ranged\BowsTable;
use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
use DrdPlus\Tables\Measurements\Weight\Weight;
use DrdPlus\Tables\Tables;
use DrdPlus\Tests\Tables\Armaments\Weapons\Ranged\Partials\RangedWeaponsTableTest;

class BowsTableTest extends RangedWeaponsTableTest
{

    protected function getRowHeaderName(): string
    {
        return 'weapon';
    }

    public function provideArmamentAndNameWithValue(): array
    {
        return [
            [RangedWeaponCode::SHORT_BOW, BowsTable::REQUIRED_STRENGTH, -1],
            [RangedWeaponCode::SHORT_BOW, BowsTable::MAXIMAL_APPLICABLE_STRENGTH, 3],
            [RangedWeaponCode::SHORT_BOW, BowsTable::OFFENSIVENESS, 2],
            [RangedWeaponCode::SHORT_BOW, BowsTable::WOUNDS, 1],
            [RangedWeaponCode::SHORT_BOW, BowsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::STAB],
            [RangedWeaponCode::SHORT_BOW, BowsTable::RANGE, 24],
            [RangedWeaponCode::SHORT_BOW, BowsTable::COVER, 2],
            [RangedWeaponCode::SHORT_BOW, BowsTable::WEIGHT, 1.0],
            [RangedWeaponCode::SHORT_BOW, BowsTable::TWO_HANDED_ONLY, true],

            [RangedWeaponCode::LONG_BOW, BowsTable::REQUIRED_STRENGTH, 5],
            [RangedWeaponCode::LONG_BOW, BowsTable::MAXIMAL_APPLICABLE_STRENGTH, 7],
            [RangedWeaponCode::LONG_BOW, BowsTable::OFFENSIVENESS, 3],
            [RangedWeaponCode::LONG_BOW, BowsTable::WOUNDS, 4],
            [RangedWeaponCode::LONG_BOW, BowsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::STAB],
            [RangedWeaponCode::LONG_BOW, BowsTable::RANGE, 27],
            [RangedWeaponCode::LONG_BOW, BowsTable::COVER, 2],
            [RangedWeaponCode::LONG_BOW, BowsTable::WEIGHT, 1.2],
            [RangedWeaponCode::LONG_BOW, BowsTable::TWO_HANDED_ONLY, true],

            [RangedWeaponCode::SHORT_COMPOSITE_BOW, BowsTable::REQUIRED_STRENGTH, 1],
            [RangedWeaponCode::SHORT_COMPOSITE_BOW, BowsTable::MAXIMAL_APPLICABLE_STRENGTH, 6],
            [RangedWeaponCode::SHORT_COMPOSITE_BOW, BowsTable::OFFENSIVENESS, 3],
            [RangedWeaponCode::SHORT_COMPOSITE_BOW, BowsTable::WOUNDS, 2],
            [RangedWeaponCode::SHORT_COMPOSITE_BOW, BowsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::STAB],
            [RangedWeaponCode::SHORT_COMPOSITE_BOW, BowsTable::RANGE, 26],
            [RangedWeaponCode::SHORT_COMPOSITE_BOW, BowsTable::COVER, 2],
            [RangedWeaponCode::SHORT_COMPOSITE_BOW, BowsTable::WEIGHT, 1.0],
            [RangedWeaponCode::SHORT_COMPOSITE_BOW, BowsTable::TWO_HANDED_ONLY, true],

            [RangedWeaponCode::LONG_COMPOSITE_BOW, BowsTable::REQUIRED_STRENGTH, 5],
            [RangedWeaponCode::LONG_COMPOSITE_BOW, BowsTable::MAXIMAL_APPLICABLE_STRENGTH, 9],
            [RangedWeaponCode::LONG_COMPOSITE_BOW, BowsTable::OFFENSIVENESS, 4],
            [RangedWeaponCode::LONG_COMPOSITE_BOW, BowsTable::WOUNDS, 5],
            [RangedWeaponCode::LONG_COMPOSITE_BOW, BowsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::STAB],
            [RangedWeaponCode::LONG_COMPOSITE_BOW, BowsTable::RANGE, 29],
            [RangedWeaponCode::LONG_COMPOSITE_BOW, BowsTable::COVER, 2],
            [RangedWeaponCode::LONG_COMPOSITE_BOW, BowsTable::WEIGHT, 1.5],
            [RangedWeaponCode::LONG_COMPOSITE_BOW, BowsTable::TWO_HANDED_ONLY, true],

            [RangedWeaponCode::POWER_BOW, BowsTable::REQUIRED_STRENGTH, 7],
            [RangedWeaponCode::POWER_BOW, BowsTable::MAXIMAL_APPLICABLE_STRENGTH, 12],
            [RangedWeaponCode::POWER_BOW, BowsTable::OFFENSIVENESS, 5],
            [RangedWeaponCode::POWER_BOW, BowsTable::WOUNDS, 6],
            [RangedWeaponCode::POWER_BOW, BowsTable::WOUNDS_TYPE, PhysicalWoundTypeCode::STAB],
            [RangedWeaponCode::POWER_BOW, BowsTable::RANGE, 31],
            [RangedWeaponCode::POWER_BOW, BowsTable::COVER, 2],
            [RangedWeaponCode::POWER_BOW, BowsTable::WEIGHT, 2.0],
            [RangedWeaponCode::POWER_BOW, BowsTable::TWO_HANDED_ONLY, true],
        ];
    }

    /**
     * @test
     */
    public function I_can_get_maximal_applicable_strength_easily(): void
    {
        $bowsTable = new BowsTable();
        self::assertSame(
            3,
            $bowsTable->getMaximalApplicableStrengthOf(RangedWeaponCode::getIt(RangedWeaponCode::SHORT_BOW))
        );
        self::assertSame(
            12,
            $bowsTable->getMaximalApplicableStrengthOf(RangedWeaponCode::POWER_BOW)
        );
    }

    /**
     * @test
     */
    public function I_can_get_maximal_applicable_strength_for_custom_bow(): void
    {
        self::assertTrue(
            RangedWeaponCode::addNewRangedWeaponCode(
                'David Bowie',
                WeaponCategoryCode::getIt(WeaponCategoryCode::BOWS),
                []
            )
        );
        $customBowCode = RangedWeaponCode::getIt('David Bowie');
        $bowsTable = new BowsTable();
        self::assertTrue(
            $bowsTable->addNewBow(
                $customBowCode,
                Strength::getIt(123),
                new DistanceBonus(5, Tables::getIt()->getDistanceTable()),
                1,
                2,
                PhysicalWoundTypeCode::getIt(PhysicalWoundTypeCode::STAB),
                3,
                new Weight(5, Weight::KG, Tables::getIt()->getWeightTable()),
                true,
                Strength::getIt(12) // maximal applicable strength
            )
        );
        self::assertSame(12, $bowsTable->getMaximalApplicableStrengthOf($customBowCode));
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Armaments\Weapons\Ranged\Exceptions\UnknownBow
     * @expectedExceptionMessageRegExp ~javelin~
     */
    public function I_can_not_get_maximal_applicable_strength_for_unknown_bow(): void
    {
        (new BowsTable())->getMaximalApplicableStrengthOf(RangedWeaponCode::JAVELIN);
    }

}