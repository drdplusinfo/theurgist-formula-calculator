<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Professions;

use DrdPlus\Codes\ProfessionCode;
use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Tables\Professions\ProfessionPrimaryPropertiesTable;
use DrdPlus\Tests\Tables\TableTest;

class ProfessionPrimaryPropertiesTableTest extends TableTest
{
    /**
     * @test
     */
    public function I_can_get_header()
    {
        $professionPrimaryPropertiesTable = new ProfessionPrimaryPropertiesTable();
        self::assertSame(
            [['profession', 'first_primary_property', 'second_primary_property']],
            $professionPrimaryPropertiesTable->getHeader()
        );
    }

    /**
     * @test
     * @dataProvider provideProfessionAndExpectedPrimaryProperties
     * @param string $profession
     * @param string $firstPrimaryProperty
     * @param string $secondPrimaryProperty
     */
    public function I_can_get_primary_properties_for_each_profession($profession, $firstPrimaryProperty, $secondPrimaryProperty)
    {
        $professionPrimaryPropertiesTable = new ProfessionPrimaryPropertiesTable();
        $professionCode = ProfessionCode::getIt($profession);
        $expectedProperties = $firstPrimaryProperty && $secondPrimaryProperty
            ? [PropertyCode::getIt($firstPrimaryProperty), PropertyCode::getIt($secondPrimaryProperty)]
            : [];
        $givenProperties = $professionPrimaryPropertiesTable->getPrimaryPropertiesOf($professionCode);
        self::assertSame($expectedProperties, $givenProperties);
    }

    public function provideProfessionAndExpectedPrimaryProperties()
    {
        return [
            [ProfessionCode::COMMONER, false, false],
            [ProfessionCode::FIGHTER, PropertyCode::STRENGTH, PropertyCode::AGILITY],
            [ProfessionCode::RANGER, PropertyCode::KNACK, PropertyCode::STRENGTH],
            [ProfessionCode::THIEF, PropertyCode::AGILITY, PropertyCode::KNACK],
            [ProfessionCode::WIZARD, PropertyCode::WILL, PropertyCode::INTELLIGENCE],
            [ProfessionCode::THEURGIST, PropertyCode::INTELLIGENCE, PropertyCode::CHARISMA],
            [ProfessionCode::PRIEST, PropertyCode::CHARISMA, PropertyCode::WILL],
        ];
    }

    /**
     * @test
     */
    public function I_can_not_get_primary_properties_for_unknown_profession()
    {
        $this->expectException(\DrdPlus\Tables\Professions\Exceptions\UnknownProfession::class);
        $this->expectExceptionMessageRegExp('~ninja~');
        (new ProfessionPrimaryPropertiesTable())->getPrimaryPropertiesOf($this->createProfessionCode('ninja'));
    }

    /**
     * @param string $value
     * @return ProfessionCode|\Mockery\MockInterface
     */
    private function createProfessionCode($value)
    {
        $professionCode = $this->mockery(ProfessionCode::class);
        $professionCode->shouldReceive('__toString')
            ->andReturn((string)$value);

        return $professionCode;
    }

}