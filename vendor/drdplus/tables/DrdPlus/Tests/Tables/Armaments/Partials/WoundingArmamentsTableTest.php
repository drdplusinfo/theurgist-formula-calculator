<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Armaments\Partials;

use DrdPlus\Tables\Armaments\Partials\WoundingArmamentsTable;
use DrdPlus\Tests\Tables\TableTest;
use Granam\String\StringTools;

abstract class WoundingArmamentsTableTest extends TableTest
{

    /**
     * @test
     */
    public function I_can_get_all_values()
    {
        $sutClass = self::getSutClass();
        /** @var WoundingArmamentsTable $weaponlikeTable */
        $weaponlikeTable = new $sutClass();
        self::assertSame(
            $this->assembleIndexedValues($this->provideArmamentAndNameWithValue()),
            $weaponlikeTable->getIndexedValues()
        );
    }

    private function assembleIndexedValues(array $values)
    {
        $indexedValues = [];
        foreach ($values as [$weapon, $parameterName, $parameterValue]) {
            if (!array_key_exists($weapon, $indexedValues)) {
                $indexedValues[$weapon] = [];
            }
            $indexedValues[$weapon][$parameterName] = $parameterValue;
        }

        return $indexedValues;
    }

    /**
     * @return array|mixed[][]
     */
    abstract public function provideArmamentAndNameWithValue(): array;

    /**
     * @test
     * @dataProvider provideArmamentAndNameWithValue
     * @param string $shootingArmamentCode
     * @param string $valueName
     * @param mixed $expectedValue
     */
    public function I_can_get_values_for_every_armament($shootingArmamentCode, $valueName, $expectedValue)
    {
        $sutClass = self::getSutClass();
        /** @var WoundingArmamentsTable $forAttackTable */
        $forAttackTable = new $sutClass();

        $value = $forAttackTable->getValue([$shootingArmamentCode], $valueName);
        self::assertSame($expectedValue, $value);

        $getValueOf = $this->assembleValueGetter($valueName);
        self::assertSame($value, $forAttackTable->$getValueOf($shootingArmamentCode));
    }

    protected function assembleValueGetter($valueName): string
    {
        return 'get' . StringTools::snakeCaseToCamelCase($valueName) . 'Of';
    }
}