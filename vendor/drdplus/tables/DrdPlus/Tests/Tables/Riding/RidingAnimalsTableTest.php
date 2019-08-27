<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Riding;

use DrdPlus\Codes\Transport\RidingAnimalCode;
use DrdPlus\Codes\Transport\RidingAnimalPropertyCode;
use DrdPlus\Tables\Riding\DefianceOfWildPercents;
use DrdPlus\Tables\Riding\RidingAnimalsTable;
use DrdPlus\Tests\Tables\TableTest;

class RidingAnimalsTableTest extends TableTest
{
    /**
     * @test
     */
    public function I_can_get_header()
    {
        self::assertSame(
            [
                [
                    'animal',
                    RidingAnimalPropertyCode::SPEED,
                    RidingAnimalPropertyCode::ENDURANCE,
                    RidingAnimalPropertyCode::MAXIMAL_LOAD,
                    RidingAnimalPropertyCode::MAXIMAL_LOAD_IN_KG,
                    RidingAnimalPropertyCode::DEFIANCE,
                ]
            ],
            (new RidingAnimalsTable())->getHeader()
        );
    }

    /**
     * @test
     */
    public function I_can_get_all_values()
    {
        self::assertSame(
            $expectedValues = [
                RidingAnimalCode::HORSE => [
                    RidingAnimalPropertyCode::SPEED => 4,
                    RidingAnimalPropertyCode::ENDURANCE => 4,
                    RidingAnimalPropertyCode::MAXIMAL_LOAD => 12,
                    RidingAnimalPropertyCode::MAXIMAL_LOAD_IN_KG => 160,
                    RidingAnimalPropertyCode::DEFIANCE => 3,
                ],
                RidingAnimalCode::DRAFT_HORSE => [
                    RidingAnimalPropertyCode::SPEED => 0,
                    RidingAnimalPropertyCode::ENDURANCE => 7,
                    RidingAnimalPropertyCode::MAXIMAL_LOAD => 15,
                    RidingAnimalPropertyCode::MAXIMAL_LOAD_IN_KG => 220,
                    RidingAnimalPropertyCode::DEFIANCE => 4,
                ],
                RidingAnimalCode::RIDING_HORSE => [
                    RidingAnimalPropertyCode::SPEED => 6,
                    RidingAnimalPropertyCode::ENDURANCE => 3,
                    RidingAnimalPropertyCode::MAXIMAL_LOAD => 11,
                    RidingAnimalPropertyCode::MAXIMAL_LOAD_IN_KG => 140,
                    RidingAnimalPropertyCode::DEFIANCE => 4,
                ],
                RidingAnimalCode::WAR_HORSE => [
                    RidingAnimalPropertyCode::SPEED => 3,
                    RidingAnimalPropertyCode::ENDURANCE => 5,
                    RidingAnimalPropertyCode::MAXIMAL_LOAD => 13,
                    RidingAnimalPropertyCode::MAXIMAL_LOAD_IN_KG => 160,
                    RidingAnimalPropertyCode::DEFIANCE => 3,
                ],
                RidingAnimalCode::CAMEL => [
                    RidingAnimalPropertyCode::SPEED => 0,
                    RidingAnimalPropertyCode::ENDURANCE => 10,
                    RidingAnimalPropertyCode::MAXIMAL_LOAD => 14,
                    RidingAnimalPropertyCode::MAXIMAL_LOAD_IN_KG => 200,
                    RidingAnimalPropertyCode::DEFIANCE => 2,
                ],
                RidingAnimalCode::ELEPHANT => [
                    RidingAnimalPropertyCode::SPEED => 2,
                    RidingAnimalPropertyCode::ENDURANCE => 14,
                    RidingAnimalPropertyCode::MAXIMAL_LOAD => 21,
                    RidingAnimalPropertyCode::MAXIMAL_LOAD_IN_KG => 450,
                    RidingAnimalPropertyCode::DEFIANCE => 8,
                ],
                RidingAnimalCode::YAK => [
                    RidingAnimalPropertyCode::SPEED => -2,
                    RidingAnimalPropertyCode::ENDURANCE => 9,
                    RidingAnimalPropertyCode::MAXIMAL_LOAD => 16,
                    RidingAnimalPropertyCode::MAXIMAL_LOAD_IN_KG => 240,
                    RidingAnimalPropertyCode::DEFIANCE => 5,
                ],
                RidingAnimalCode::LAME => [
                    RidingAnimalPropertyCode::SPEED => -3,
                    RidingAnimalPropertyCode::ENDURANCE => 8,
                    RidingAnimalPropertyCode::MAXIMAL_LOAD => 12,
                    RidingAnimalPropertyCode::MAXIMAL_LOAD_IN_KG => 160,
                    RidingAnimalPropertyCode::DEFIANCE => 2,
                ],
                RidingAnimalCode::DONKEY => [
                    RidingAnimalPropertyCode::SPEED => -2,
                    RidingAnimalPropertyCode::ENDURANCE => 4,
                    RidingAnimalPropertyCode::MAXIMAL_LOAD => 9,
                    RidingAnimalPropertyCode::MAXIMAL_LOAD_IN_KG => 110,
                    RidingAnimalPropertyCode::DEFIANCE => 4,
                ],
                RidingAnimalCode::PONY => [
                    RidingAnimalPropertyCode::SPEED => -2,
                    RidingAnimalPropertyCode::ENDURANCE => 4,
                    RidingAnimalPropertyCode::MAXIMAL_LOAD => 9,
                    RidingAnimalPropertyCode::MAXIMAL_LOAD_IN_KG => 110,
                    RidingAnimalPropertyCode::DEFIANCE => 4,
                ],
                RidingAnimalCode::HINNY => [
                    RidingAnimalPropertyCode::SPEED => -2,
                    RidingAnimalPropertyCode::ENDURANCE => 6,
                    RidingAnimalPropertyCode::MAXIMAL_LOAD => 11,
                    RidingAnimalPropertyCode::MAXIMAL_LOAD_IN_KG => 140,
                    RidingAnimalPropertyCode::DEFIANCE => 6,
                ],
                RidingAnimalCode::COW => [
                    RidingAnimalPropertyCode::SPEED => -4,
                    RidingAnimalPropertyCode::ENDURANCE => 4,
                    RidingAnimalPropertyCode::MAXIMAL_LOAD => 14,
                    RidingAnimalPropertyCode::MAXIMAL_LOAD_IN_KG => 200,
                    RidingAnimalPropertyCode::DEFIANCE => 4,
                ],
                RidingAnimalCode::BULL => [
                    RidingAnimalPropertyCode::SPEED => -2,
                    RidingAnimalPropertyCode::ENDURANCE => 8,
                    RidingAnimalPropertyCode::MAXIMAL_LOAD => 16,
                    RidingAnimalPropertyCode::MAXIMAL_LOAD_IN_KG => 250,
                    RidingAnimalPropertyCode::DEFIANCE => 7,
                ],
                RidingAnimalCode::UNICORN => [
                    RidingAnimalPropertyCode::SPEED => 8,
                    RidingAnimalPropertyCode::ENDURANCE => 3,
                    RidingAnimalPropertyCode::MAXIMAL_LOAD => 9,
                    RidingAnimalPropertyCode::MAXIMAL_LOAD_IN_KG => 110,
                    RidingAnimalPropertyCode::DEFIANCE => 12,
                ],
            ],
            $givenValues = (new RidingAnimalsTable())->getIndexedValues()
        );
    }

    /**
     * @test
     */
    public function I_can_get_speed()
    {
        self::assertSame(-2, (new RidingAnimalsTable())->getSpeed(RidingAnimalCode::getIt(RidingAnimalCode::BULL)));
    }

    /**
     * @test
     */
    public function I_can_get_endurance()
    {
        self::assertSame(4, (new RidingAnimalsTable())->getEndurance(RidingAnimalCode::getIt(RidingAnimalCode::COW)));
    }

    /**
     * @test
     */
    public function I_can_get_maximal_load()
    {
        self::assertSame(11, (new RidingAnimalsTable())->getMaximalLoad(RidingAnimalCode::getIt(RidingAnimalCode::HINNY)));
    }

    /**
     * @test
     */
    public function I_can_get_maximal_load_in_kg()
    {
        self::assertSame(110, (new RidingAnimalsTable())->getMaximalLoadInKg(RidingAnimalCode::getIt(RidingAnimalCode::PONY)));
    }

    /**
     * @test
     */
    public function I_can_get_defiance_of_domesticated()
    {
        self::assertSame(
            5,
            (new RidingAnimalsTable())->getDefianceOfDomesticated(
                RidingAnimalCode::getIt(RidingAnimalCode::YAK),
                false
            )
        );
        self::assertSame(
            7,
            (new RidingAnimalsTable())->getDefianceOfDomesticated(
                RidingAnimalCode::getIt(RidingAnimalCode::YAK),
                true
            )
        );
    }

    /**
     * @test
     */
    public function I_can_get_defiance_of_wild()
    {
        self::assertSame(
            19,
            (new RidingAnimalsTable())->getDefianceOfWild(
                RidingAnimalCode::getIt(RidingAnimalCode::BULL),
                $this->createDefianceOfWildPercents(100),
                false
            )
        );
        self::assertSame(
            18,
            (new RidingAnimalsTable())->getDefianceOfWild(
                RidingAnimalCode::getIt(RidingAnimalCode::BULL),
                $this->createDefianceOfWildPercents(25),
                false
            )
        );
        self::assertSame(
            17,
            (new RidingAnimalsTable())->getDefianceOfWild(
                RidingAnimalCode::getIt(RidingAnimalCode::BULL),
                $this->createDefianceOfWildPercents(24),
                false
            )
        );
    }

    /**
     * @param $percents
     * @return \Mockery\MockInterface|DefianceOfWildPercents
     */
    private function createDefianceOfWildPercents($percents)
    {
        $defianceOfWildPercents = $this->mockery(DefianceOfWildPercents::class);
        $defianceOfWildPercents->shouldReceive('getRate')
            ->andReturn($percents / 100);

        return $defianceOfWildPercents;
    }

}