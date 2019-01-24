<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\Armaments\Armors;

use DrdPlus\Codes\Armaments\BodyArmorCode;
use DrdPlus\Properties\Base\Strength;
use DrdPlus\Tables\Armaments\Armors\BodyArmorsTable;
use DrdPlus\Calculations\SumAndRound;
use DrdPlus\Tables\Measurements\Weight\Weight;
use DrdPlus\Tables\Tables;
use Granam\Integer\PositiveIntegerObject;
use Granam\Tools\ValueDescriber;
use Mockery\MockInterface;

class BodyArmorsTableTest extends AbstractArmorsTableTest
{
    /**
     * @test
     */
    public function I_can_get_header(): void
    {
        $armorsTable = new BodyArmorsTable();
        self::assertSame(
            [[$this->getRowHeaderName(), 'required_strength', 'restriction', 'protection', 'weight', 'rounds_to_put_on']],
            $armorsTable->getHeader()
        );
    }

    public function provideArmorAndValue(): array
    {
        return [
            [BodyArmorCode::WITHOUT_ARMOR, BodyArmorsTable::REQUIRED_STRENGTH, false],
            [BodyArmorCode::WITHOUT_ARMOR, BodyArmorsTable::RESTRICTION, 0],
            [BodyArmorCode::WITHOUT_ARMOR, BodyArmorsTable::PROTECTION, 0],
            [BodyArmorCode::WITHOUT_ARMOR, BodyArmorsTable::WEIGHT, 0.0],
            [BodyArmorCode::WITHOUT_ARMOR, BodyArmorsTable::ROUNDS_TO_PUT_ON, 0],

            [BodyArmorCode::PADDED_ARMOR, BodyArmorsTable::REQUIRED_STRENGTH, -2],
            [BodyArmorCode::PADDED_ARMOR, BodyArmorsTable::RESTRICTION, 0],
            [BodyArmorCode::PADDED_ARMOR, BodyArmorsTable::PROTECTION, 2],
            [BodyArmorCode::PADDED_ARMOR, BodyArmorsTable::WEIGHT, 4.0],
            [BodyArmorCode::PADDED_ARMOR, BodyArmorsTable::ROUNDS_TO_PUT_ON, 1],

            [BodyArmorCode::LEATHER_ARMOR, BodyArmorsTable::REQUIRED_STRENGTH, 1],
            [BodyArmorCode::LEATHER_ARMOR, BodyArmorsTable::RESTRICTION, 0],
            [BodyArmorCode::LEATHER_ARMOR, BodyArmorsTable::PROTECTION, 3],
            [BodyArmorCode::LEATHER_ARMOR, BodyArmorsTable::WEIGHT, 6.0],
            [BodyArmorCode::LEATHER_ARMOR, BodyArmorsTable::ROUNDS_TO_PUT_ON, 1],

            [BodyArmorCode::HOBNAILED_ARMOR, BodyArmorsTable::REQUIRED_STRENGTH, 3],
            [BodyArmorCode::HOBNAILED_ARMOR, BodyArmorsTable::RESTRICTION, 0],
            [BodyArmorCode::HOBNAILED_ARMOR, BodyArmorsTable::PROTECTION, 4],
            [BodyArmorCode::HOBNAILED_ARMOR, BodyArmorsTable::WEIGHT, 8.0],
            [BodyArmorCode::HOBNAILED_ARMOR, BodyArmorsTable::ROUNDS_TO_PUT_ON, 2],

            [BodyArmorCode::CHAINMAIL_ARMOR, BodyArmorsTable::REQUIRED_STRENGTH, 5],
            [BodyArmorCode::CHAINMAIL_ARMOR, BodyArmorsTable::RESTRICTION, -1],
            [BodyArmorCode::CHAINMAIL_ARMOR, BodyArmorsTable::PROTECTION, 6],
            [BodyArmorCode::CHAINMAIL_ARMOR, BodyArmorsTable::WEIGHT, 15.0],
            [BodyArmorCode::CHAINMAIL_ARMOR, BodyArmorsTable::ROUNDS_TO_PUT_ON, 2],

            [BodyArmorCode::SCALE_ARMOR, BodyArmorsTable::REQUIRED_STRENGTH, 7],
            [BodyArmorCode::SCALE_ARMOR, BodyArmorsTable::RESTRICTION, -2],
            [BodyArmorCode::SCALE_ARMOR, BodyArmorsTable::PROTECTION, 7],
            [BodyArmorCode::SCALE_ARMOR, BodyArmorsTable::WEIGHT, 20.0],
            [BodyArmorCode::SCALE_ARMOR, BodyArmorsTable::ROUNDS_TO_PUT_ON, 3],

            [BodyArmorCode::PLATE_ARMOR, BodyArmorsTable::REQUIRED_STRENGTH, 10],
            [BodyArmorCode::PLATE_ARMOR, BodyArmorsTable::RESTRICTION, -3],
            [BodyArmorCode::PLATE_ARMOR, BodyArmorsTable::PROTECTION, 9],
            [BodyArmorCode::PLATE_ARMOR, BodyArmorsTable::WEIGHT, 30.0],
            [BodyArmorCode::PLATE_ARMOR, BodyArmorsTable::ROUNDS_TO_PUT_ON, 3],

            [BodyArmorCode::FULL_PLATE_ARMOR, BodyArmorsTable::REQUIRED_STRENGTH, 12],
            [BodyArmorCode::FULL_PLATE_ARMOR, BodyArmorsTable::RESTRICTION, -4],
            [BodyArmorCode::FULL_PLATE_ARMOR, BodyArmorsTable::PROTECTION, 10],
            [BodyArmorCode::FULL_PLATE_ARMOR, BodyArmorsTable::WEIGHT, 35.0],
            [BodyArmorCode::FULL_PLATE_ARMOR, BodyArmorsTable::ROUNDS_TO_PUT_ON, 4],
        ];
    }

    /**
     * @test
     */
    public function I_get_rounds_to_put_on_armor_related_to_its_protection()
    {
        $bodyArmorsTable = new BodyArmorsTable();
        foreach (BodyArmorCode::getPossibleValues() as $bodyArmorCode) {
            self::assertSame(
                SumAndRound::ceiledThird($bodyArmorsTable->getProtectionOf($bodyArmorCode)),
                $bodyArmorsTable->getRoundsToPutOnOf($bodyArmorCode)
            );
        }
    }

    /**
     * @test
     */
    public function I_can_add_custom_body_armor()
    {
        $bodyArmorCode = $this->createBodyArmorCode($name = uniqid('foo', true));
        $requiredStrength = Strength::getIt(132);
        $restriction = 2;
        $protection = 5267;
        $weight = new Weight(54, Weight::KG, Tables::getIt()->getWeightTable());
        $roundsToPutOn = new PositiveIntegerObject(55);
        $bodyArmorsTable = Tables::getIt()->getBodyArmorsTable();
        for ($attempt = 1; $attempt < 3; $attempt++) {
            $added = $bodyArmorsTable->addCustomBodyArmor($bodyArmorCode, $requiredStrength, $restriction, $protection, $weight, $roundsToPutOn);
            if ($attempt === 1) {
                self::assertTrue($added, 'Adding brand new body armor should return true');
            } else {
                self::assertFalse($added, 'Adding very same body armor should return false and skip it');
            }
            $indexedValues = $bodyArmorsTable->getIndexedValues();
            self::assertTrue(
                array_key_exists($name, $indexedValues),
                "Expected '$name' as a key from new armor name in " . ValueDescriber::describe($indexedValues)
            );
            self::assertSame(132, $bodyArmorsTable->getRequiredStrengthOf($bodyArmorCode));
            self::assertSame(2, $bodyArmorsTable->getRestrictionOf($bodyArmorCode));
            self::assertSame(5267, $bodyArmorsTable->getProtectionOf($bodyArmorCode));
            self::assertSame(54.0, $bodyArmorsTable->getWeightOf($bodyArmorCode));
            self::assertSame(55, $bodyArmorsTable->getRoundsToPutOnOf($bodyArmorCode));
        }
    }

    /**
     * @param string $name
     * @return BodyArmorCode|MockInterface
     */
    private function createBodyArmorCode(string $name): BodyArmorCode
    {
        $bodyArmorCode = $this->mockery(BodyArmorCode::class);
        $bodyArmorCode->shouldReceive('getValue')
            ->andReturn($name);
        $bodyArmorCode->shouldReceive('__toString')
            ->andReturn($name);

        return $bodyArmorCode;
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Armaments\Armors\Exceptions\DifferentBodyArmorIsUnderSameName
     * @dataProvider provideSlightlyDifferentArmorProperties
     * @param int $strengthValue
     * @param int $restrictionValue
     * @param int $protectionValue
     * @param int $weightValue
     * @param int $roundsToPutOnValue
     * @param int $newStrengthValue
     * @param int $newRestrictionValue
     * @param int $newProtectionValue
     * @param int $newWeightValue
     * @param int $newRoundsToPutOnValue
     */
    public function I_can_not_add_new_custom_body_armor_under_same_name_but_different_properties(
        int $strengthValue,
        int $restrictionValue,
        int $protectionValue,
        int $weightValue,
        int $roundsToPutOnValue,
        int $newStrengthValue,
        int $newRestrictionValue,
        int $newProtectionValue,
        int $newWeightValue,
        int $newRoundsToPutOnValue
    )
    {
        $bodyArmorCode = $this->createBodyArmorCode(uniqid('bar', true));
        $bodyArmorsTable = Tables::getIt()->getBodyArmorsTable();
        $added = $bodyArmorsTable->addCustomBodyArmor(
            $bodyArmorCode,
            Strength::getIt($strengthValue),
            $restrictionValue,
            $protectionValue,
            new Weight($weightValue, Weight::KG, Tables::getIt()->getWeightTable()),
            new PositiveIntegerObject($roundsToPutOnValue)
        );
        self::assertTrue($added, 'Adding brand new body armor should return true');
        $bodyArmorsTable->addCustomBodyArmor(
            $bodyArmorCode,
            Strength::getIt($newStrengthValue),
            $newRestrictionValue,
            $newProtectionValue,
            new Weight($newWeightValue, Weight::KG, Tables::getIt()->getWeightTable()),
            new PositiveIntegerObject($newRoundsToPutOnValue)
        );
    }

    public function provideSlightlyDifferentArmorProperties()
    {

        return [
            [1, 20, 300, 4000, 50000, 2, 20, 300, 4000, 50000],
            [1, 20, 300, 4000, 50000, 1, 21, 300, 4000, 50000],
            [1, 20, 300, 4000, 50000, 1, 20, 301, 4000, 50000],
            [1, 20, 300, 4000, 50000, 1, 20, 300, 3999, 50000],
            [1, 20, 300, 4000, 50000, 1, 20, 300, 4000, 50001],
        ];
    }

}