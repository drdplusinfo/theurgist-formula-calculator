<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\Armaments\Armors;

use DrdPlus\Codes\Armaments\HelmCode;
use DrdPlus\Properties\Base\Strength;
use DrdPlus\Tables\Armaments\Armors\AbstractArmorsTable;
use DrdPlus\Tables\Measurements\Weight\Weight;
use DrdPlus\Tables\Tables;
use Granam\Tools\ValueDescriber;
use Mockery\MockInterface;

class HelmsTableTest extends AbstractArmorsTableTest
{
    public function provideArmorAndValue(): array
    {
        return [
            [HelmCode::WITHOUT_HELM, AbstractArmorsTable::REQUIRED_STRENGTH, false],
            [HelmCode::WITHOUT_HELM, AbstractArmorsTable::RESTRICTION, 0],
            [HelmCode::WITHOUT_HELM, AbstractArmorsTable::PROTECTION, 0],
            [HelmCode::WITHOUT_HELM, AbstractArmorsTable::WEIGHT, 0.0],

            [HelmCode::LEATHER_CAP, AbstractArmorsTable::REQUIRED_STRENGTH, 0],
            [HelmCode::LEATHER_CAP, AbstractArmorsTable::RESTRICTION, 0],
            [HelmCode::LEATHER_CAP, AbstractArmorsTable::PROTECTION, 1],
            [HelmCode::LEATHER_CAP, AbstractArmorsTable::WEIGHT, 0.3],

            [HelmCode::CHAINMAIL_HOOD, AbstractArmorsTable::REQUIRED_STRENGTH, 2],
            [HelmCode::CHAINMAIL_HOOD, AbstractArmorsTable::RESTRICTION, 0],
            [HelmCode::CHAINMAIL_HOOD, AbstractArmorsTable::PROTECTION, 2],
            [HelmCode::CHAINMAIL_HOOD, AbstractArmorsTable::WEIGHT, 1.2],

            [HelmCode::CONICAL_HELM, AbstractArmorsTable::REQUIRED_STRENGTH, 3],
            [HelmCode::CONICAL_HELM, AbstractArmorsTable::RESTRICTION, -1],
            [HelmCode::CONICAL_HELM, AbstractArmorsTable::PROTECTION, 3],
            [HelmCode::CONICAL_HELM, AbstractArmorsTable::WEIGHT, 1.5],

            [HelmCode::FULL_HELM, AbstractArmorsTable::REQUIRED_STRENGTH, 4],
            [HelmCode::FULL_HELM, AbstractArmorsTable::RESTRICTION, -1],
            [HelmCode::FULL_HELM, AbstractArmorsTable::PROTECTION, 4],
            [HelmCode::FULL_HELM, AbstractArmorsTable::WEIGHT, 2.0],

            [HelmCode::BARREL_HELM, AbstractArmorsTable::REQUIRED_STRENGTH, 5],
            [HelmCode::BARREL_HELM, AbstractArmorsTable::RESTRICTION, -2],
            [HelmCode::BARREL_HELM, AbstractArmorsTable::PROTECTION, 5],
            [HelmCode::BARREL_HELM, AbstractArmorsTable::WEIGHT, 3.0],

            [HelmCode::GREAT_HELM, AbstractArmorsTable::REQUIRED_STRENGTH, 7],
            [HelmCode::GREAT_HELM, AbstractArmorsTable::RESTRICTION, -3],
            [HelmCode::GREAT_HELM, AbstractArmorsTable::PROTECTION, 7],
            [HelmCode::GREAT_HELM, AbstractArmorsTable::WEIGHT, 4.0],
        ];
    }

    /**
     * @test
     */
    public function I_can_add_custom_helm()
    {
        $helmCode = $this->createHelm($name = uniqid('foo', true));
        $requiredStrength = Strength::getIt(132);
        $restriction = 2;
        $protection = 5267;
        $weight = new Weight(54, Weight::KG, Tables::getIt()->getWeightTable());
        $helmsTable = Tables::getIt()->getHelmsTable();
        for ($attempt = 1; $attempt < 3; $attempt++) {
            $added = $helmsTable->addCustomHelm($helmCode, $requiredStrength, $restriction, $protection, $weight);
            if ($attempt === 1) {
                self::assertTrue($added, 'Adding brand new helm should return true');
            } else {
                self::assertFalse($added, 'Adding very same helm should return false and skip it');
            }
            $indexedValues = $helmsTable->getIndexedValues();
            self::assertTrue(
                array_key_exists($name, $indexedValues),
                "Expected '$name' as a key from new helm name in " . ValueDescriber::describe($indexedValues)
            );
            self::assertSame(132, $helmsTable->getRequiredStrengthOf($helmCode));
            self::assertSame(2, $helmsTable->getRestrictionOf($helmCode));
            self::assertSame(5267, $helmsTable->getProtectionOf($helmCode));
            self::assertSame(54.0, $helmsTable->getWeightOf($helmCode));
        }
    }

    /**
     * @param string $name
     * @return HelmCode|MockInterface
     */
    private function createHelm(string $name): HelmCode
    {
        $helmCode = $this->mockery(HelmCode::class);
        $helmCode->shouldReceive('getValue')
            ->andReturn($name);
        $helmCode->shouldReceive('__toString')
            ->andReturn($name);

        return $helmCode;
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Armaments\Armors\Exceptions\DifferentHelmIsUnderSameName
     * @dataProvider provideSlightlyDifferentHelmProperties
     * @param int $strengthValue
     * @param int $restrictionValue
     * @param int $protectionValue
     * @param int $weightValue
     * @param int $newStrengthValue
     * @param int $newRestrictionValue
     * @param int $newProtectionValue
     * @param int $newWeightValue
     */
    public function I_can_not_add_new_custom_helm_under_same_name_but_different_properties(
        int $strengthValue,
        int $restrictionValue,
        int $protectionValue,
        int $weightValue,
        int $newStrengthValue,
        int $newRestrictionValue,
        int $newProtectionValue,
        int $newWeightValue
    )
    {
        $helmCode = $this->createHelm(uniqid('bar', true));
        $helmsTable = Tables::getIt()->getHelmsTable();
        $added = $helmsTable->addCustomHelm(
            $helmCode,
            Strength::getIt($strengthValue),
            $restrictionValue,
            $protectionValue,
            new Weight($weightValue, Weight::KG, Tables::getIt()->getWeightTable())
        );
        self::assertTrue($added, 'Adding brand new helm should return true');
        $helmsTable->addCustomHelm(
            $helmCode,
            Strength::getIt($newStrengthValue),
            $newRestrictionValue,
            $newProtectionValue,
            new Weight($newWeightValue, Weight::KG, Tables::getIt()->getWeightTable())
        );
    }

    public function provideSlightlyDifferentHelmProperties()
    {

        return [
            [1, 20, 300, 4000, 2, 20, 300, 4000],
            [1, 20, 300, 4000, 1, 21, 300, 4000],
            [1, 20, 300, 4000, 1, 20, 301, 4000],
            [1, 20, 300, 4000, 1, 20, 300, 3999],
        ];
    }

}