<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Environments;

use DrdPlus\Codes\Environment\MaterialCode;
use DrdPlus\Tables\Environments\MaterialResistancesTable;
use DrdPlus\Tests\Tables\TableTest;
use Granam\DiceRolls\Templates\Rolls\Roll2d6DrdPlus;
use Granam\Integer\IntegerObject;

class MaterialResistancesTableTest extends TableTest
{
    /**
     * @test
     * @dataProvider provideMaterialCodes
     * @param string $materialCodeValue
     * @param int $expectedResistance
     */
    public function I_can_get_resistance_of_material(string $materialCodeValue, int $expectedResistance)
    {
        $materialResistancesTable = new MaterialResistancesTable();
        self::assertSame(
            $expectedResistance,
            $materialResistancesTable->getResistanceOfMaterial(MaterialCode::getIt($materialCodeValue))
        );
    }

    public function provideMaterialCodes()
    {
        return [
            [MaterialCode::CLOTH_OR_PAPER_OR_ROPE, 6],
            [MaterialCode::WOOD, 12],
            [MaterialCode::BAKED_CAY, 18],
            [MaterialCode::STONE, 24],
            [MaterialCode::BRONZE, 30],
            [MaterialCode::IRON_OR_STEEL, 36],
        ];
    }

    /**
     * @test
     */
    public function I_can_not_get_resistance_for_unknown_material()
    {
        $this->expectException(\DrdPlus\Tables\Environments\Exceptions\UnknownMaterialToGetResistanceFor::class);
        $this->expectExceptionMessageRegExp('~water~');
        (new MaterialResistancesTable())->getResistanceOfMaterial($this->createMaterialCode('water'));
    }

    /**
     * @param string $value
     * @return \Mockery\MockInterface|MaterialCode
     */
    private function createMaterialCode(string $value)
    {
        $materialCode = $this->mockery(MaterialCode::class);
        $materialCode->shouldReceive('getValue')
            ->andReturn($value);
        $materialCode->shouldReceive('__toString')
            ->andReturn($value);

        return $materialCode;
    }

    /**
     * @test
     * @dataProvider provideValuesToDamageSomething
     * @param string $materialName
     * @param int $powerOfDestruction
     * @param int $roll
     * @param bool $hasItBeenDamaged
     */
    public function I_can_find_out_if_something_has_been_damaged(
        string $materialName,
        int $powerOfDestruction,
        int $roll,
        bool $hasItBeenDamaged
    )
    {
        $materialResistancesTable = new MaterialResistancesTable();
        self::assertSame(
            $hasItBeenDamaged,
            $materialResistancesTable->hasItBeenDamaged(
                MaterialCode::getIt($materialName),
                new IntegerObject($powerOfDestruction),
                $this->createRoll2d6DrdPlus($roll)
            )
        );
    }

    public function provideValuesToDamageSomething()
    {
        return [
            [MaterialCode::BAKED_CAY, 10, 7, false], // one less
            [MaterialCode::BAKED_CAY, 10, 8, false], // equal
            [MaterialCode::BAKED_CAY, 10, 9, true],
        ];
    }

    /**
     * @param $value
     * @return \Mockery\MockInterface|Roll2d6DrdPlus
     */
    private function createRoll2d6DrdPlus($value)
    {
        $roll = $this->mockery(Roll2d6DrdPlus::class);
        $roll->shouldReceive('getValue')
            ->andReturn($value);

        return $roll;
    }
}