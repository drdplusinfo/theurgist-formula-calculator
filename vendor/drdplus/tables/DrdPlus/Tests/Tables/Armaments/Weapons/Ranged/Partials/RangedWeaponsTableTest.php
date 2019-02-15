<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Armaments\Weapons\Ranged\Partials;

use DrdPlus\Codes\Armaments\RangedWeaponCode;
use DrdPlus\Codes\Armaments\WeaponCategoryCode;
use DrdPlus\Codes\Body\PhysicalWoundTypeCode;
use DrdPlus\BaseProperties\Strength;
use DrdPlus\Tables\Armaments\Weapons\Ranged\Partials\RangedWeaponsTable;
use DrdPlus\Tables\Measurements\Distance\DistanceBonus;
use DrdPlus\Tables\Measurements\Weight\Weight;
use DrdPlus\Tables\Tables;
use DrdPlus\Tests\Tables\Armaments\Partials\WeaponlikeTableTest;

abstract class RangedWeaponsTableTest extends WeaponlikeTableTest
{

    /**
     * @return string
     */
    abstract protected function getRowHeaderName(): string;

    /**
     * @test
     * @dataProvider provideValueName
     * @param string $valueName
     * @expectedException \DrdPlus\Tables\Armaments\Exceptions\UnknownRangedWeapon
     * @expectedExceptionMessageRegExp ~skull_crasher~
     */
    public function I_can_not_get_value_of_unknown_melee_weapon(string $valueName): void
    {
        $getValueNameOf = $this->assembleValueGetter($valueName);
        $sutClass = self::getSutClass();
        /** @var RangedWeaponsTable $shootingArmamentsTable */
        $shootingArmamentsTable = new $sutClass();
        $shootingArmamentsTable->$getValueNameOf('skull_crasher');
    }

    public function provideValueName(): array
    {
        return [
            [RangedWeaponsTable::REQUIRED_STRENGTH],
            [RangedWeaponsTable::OFFENSIVENESS],
            [RangedWeaponsTable::WOUNDS],
            [RangedWeaponsTable::WOUNDS_TYPE],
            [RangedWeaponsTable::RANGE],
            [RangedWeaponsTable::COVER],
            [RangedWeaponsTable::WEIGHT],
            [RangedWeaponsTable::TWO_HANDED_ONLY],
        ];
    }

    /**
     * @test
     */
    public function I_can_add_new_ranged_weapon(): void
    {
        $sut = $this->createSut();
        $name = uniqid('cannot', true);
        RangedWeaponCode::addNewRangedWeaponCode($name, $this->getWeaponCategory(), []);
        $cannot = RangedWeaponCode::getIt($name);
        $added = $sut->addCustomRangedWeapon(
            $cannot,
            $this->getWeaponCategory(),
            $requiredStrength = Strength::getIt(5),
            $range = new DistanceBonus(1, Tables::getIt()->getDistanceTable()),
            $offensiveness = 4,
            $wounds = 3,
            $woundTypeCode = PhysicalWoundTypeCode::getIt(PhysicalWoundTypeCode::STAB),
            $cover = 2,
            $weight = new Weight(5, Weight::KG, Tables::getIt()->getWeightTable()),
            $twoHandedOnly = true,
            [] // no custom parameters
        );
        self::assertTrue($added);
        self::assertSame($requiredStrength->getValue(), $sut->getRequiredStrengthOf($cannot));
        self::assertSame($range->getValue(), $sut->getRangeOf($cannot));
        self::assertSame($offensiveness, $sut->getOffensivenessOf($cannot));
        self::assertSame($wounds, $sut->getWoundsOf($cannot));
        self::assertSame($woundTypeCode->getValue(), $sut->getWoundsTypeOf($cannot));
        self::assertSame($cover, $sut->getCoverOf($cannot));
        self::assertSame($weight->getKilograms(), $sut->getWeightOf($cannot));
        self::assertSame($twoHandedOnly, $sut->getTwoHandedOnlyOf($cannot));
    }

    protected function createSut(): RangedWeaponsTable
    {
        $sutClass = self::getSutClass();

        return new $sutClass();
    }

    /**
     * @test
     */
    public function I_can_add_new_ranged_weapon_by_specific_method(): void
    {
        $sut = $this->createSut();
        $name = uniqid('nailer', true);
        $addNew = $this->assembleAddNewMethod();
        RangedWeaponCode::addNewRangedWeaponCode($name, $this->getWeaponCategory(), []);
        $nailer = RangedWeaponCode::getIt($name);
        $added = $sut->$addNew(
            $nailer,
            $requiredStrength = Strength::getIt(9),
            $range = new DistanceBonus(123, Tables::getIt()->getDistanceTable()),
            $offensiveness = 2,
            $wounds = 3,
            $woundTypeCode = PhysicalWoundTypeCode::getIt(PhysicalWoundTypeCode::CUT),
            $cover = 4,
            $weight = new Weight(5, Weight::KG, Tables::getIt()->getWeightTable()),
            $twoHandedOnly = false,
            $maximalApplicableStrength = Strength::getIt(456) // not used
        );
        self::assertTrue($added);
        self::assertSame($requiredStrength->getValue(), $sut->getRequiredStrengthOf($nailer));
        self::assertSame($range->getValue(), $sut->getRangeOf($nailer));
        self::assertSame($offensiveness, $sut->getOffensivenessOf($nailer));
        self::assertSame($wounds, $sut->getWoundsOf($nailer));
        self::assertSame($woundTypeCode->getValue(), $sut->getWoundsTypeOf($nailer));
        self::assertSame($cover, $sut->getCoverOf($nailer));
        self::assertSame($weight->getKilograms(), $sut->getWeightOf($nailer));
        self::assertSame($twoHandedOnly, $sut->getTwoHandedOnlyOf($nailer));
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Armaments\Weapons\Exceptions\NewWeaponIsNotOfRequiredType
     * @expectedExceptionMessageRegExp ~sword.+cake~
     */
    public function I_can_not_add_new_melee_weapon_with_unexpected_category()
    {
        $sut = $this->createSut();
        $name = uniqid('cake', true);
        RangedWeaponCode::addNewRangedWeaponCode($name, $this->getWeaponCategory(), []);
        $cake = RangedWeaponCode::getIt($name);
        $sut->addCustomRangedWeapon(
            $cake,
            WeaponCategoryCode::getIt(WeaponCategoryCode::SWORDS), // intentionally melee
            $requiredStrength = Strength::getIt(0),
            $range = new DistanceBonus(123, Tables::getIt()->getDistanceTable()),
            $offensiveness = 2,
            $wounds = 3,
            $woundTypeCode = PhysicalWoundTypeCode::getIt(PhysicalWoundTypeCode::CUT),
            $cover = 4,
            $weight = new Weight(5, Weight::KG, Tables::getIt()->getWeightTable()),
            $twoHandedOnly = false,
            [] // no custom parameters
        );
    }

    /**
     * @test
     * @dataProvider provideNewWeaponSlightlyChangedParameters
     * @expectedException \DrdPlus\Tables\Armaments\Weapons\Exceptions\DifferentWeaponIsUnderSameName
     * @param int $templateRequiredStrength
     * @param DistanceBonus $templateRange
     * @param int $templateOffensiveness
     * @param int $templateWounds
     * @param PhysicalWoundTypeCode $templatePhysicalWoundTypeCode
     * @param int $templateCover
     * @param Weight $templateWeight
     * @param bool $templateTwoHandedOnly
     * @param $requiredStrength
     * @param DistanceBonus $range
     * @param $offensiveness
     * @param $wounds
     * @param PhysicalWoundTypeCode $woundTypeCode
     * @param $cover
     * @param $weight
     * @param bool $twoHandedOnly
     */
    public function I_can_not_add_same_named_weapon_with_different_parameters(
        int $templateRequiredStrength,
        DistanceBonus $templateRange,
        int $templateOffensiveness,
        int $templateWounds,
        PhysicalWoundTypeCode $templatePhysicalWoundTypeCode,
        int $templateCover,
        Weight $templateWeight,
        bool $templateTwoHandedOnly,
        int $requiredStrength,
        DistanceBonus $range,
        int $offensiveness,
        int $wounds,
        PhysicalWoundTypeCode $woundTypeCode,
        int $cover,
        Weight $weight,
        bool $twoHandedOnly
    ): void
    {
        $sut = $this->createSut();
        $name = 'hailstone_' . static::getSutClass(); // unique per SUT
        $addNew = $this->assembleAddNewMethod();
        RangedWeaponCode::addNewRangedWeaponCode($name, $this->getWeaponCategory(), []);
        $hailstone = RangedWeaponCode::getIt($name);
        $added = $sut->$addNew(
            $hailstone,
            Strength::getIt($templateRequiredStrength),
            $templateRange,
            $templateOffensiveness,
            $templateWounds,
            $templatePhysicalWoundTypeCode,
            $templateCover,
            $templateWeight,
            $templateTwoHandedOnly,
            $maximalApplicableStrength = Strength::getIt(456)
        );
        self::assertTrue($added);
        $sut->$addNew(
            $hailstone,
            Strength::getIt($requiredStrength),
            $range,
            $offensiveness,
            $wounds,
            $woundTypeCode,
            $cover,
            $weight,
            $twoHandedOnly,
            $maximalApplicableStrength
        );
    }

    public function provideNewWeaponSlightlyChangedParameters(): array
    {
        $template = [
            'requiredStrength' => 0,
            'range' => new DistanceBonus(1, Tables::getIt()->getDistanceTable()),
            'offensiveness' => 2,
            'wounds' => 3,
            'woundTypeCode' => PhysicalWoundTypeCode::getIt(PhysicalWoundTypeCode::STAB),
            'cover' => 4,
            'weight' => new Weight(5, Weight::KG, Tables::getIt()->getWeightTable()),
            'twoHandedOnly' => false,
        ];
        $templateValues = array_values($template);

        return [
            array_merge($templateValues, array_values(array_merge($template, ['requiredStrength' => $template['requiredStrength'] + 1]))),
            array_merge($templateValues, array_values(array_merge($template, ['range' => new DistanceBonus(2, Tables::getIt()->getDistanceTable())]))),
            array_merge($templateValues, array_values(array_merge($template, ['offensiveness' => $template['offensiveness'] - 1]))),
            array_merge($templateValues, array_values(array_merge($template, ['wounds' => $template['wounds'] - 1]))),
            array_merge($templateValues, array_values(array_merge($template, ['wounds' => $template['wounds'] - 1]))),
            array_merge($templateValues, array_values(array_merge($template, ['woundTypeCode' => PhysicalWoundTypeCode::getIt(PhysicalWoundTypeCode::CRUSH)]))),
            array_merge($templateValues, array_values(array_merge($template, ['cover' => $template['cover'] + 2]))),
            array_merge($templateValues, array_values(array_merge($template, ['weight' => new Weight(3, Weight::KG, Tables::getIt()->getWeightTable())]))),
            array_merge($templateValues, array_values(array_merge($template, ['twoHandedOnly' => !$template['twoHandedOnly']]))),
        ];
    }

}