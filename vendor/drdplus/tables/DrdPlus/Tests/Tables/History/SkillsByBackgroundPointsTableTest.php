<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\History;

use DrdPlus\Codes\ProfessionCode;
use DrdPlus\Codes\Skills\SkillTypeCode;
use DrdPlus\Tables\History\SkillsByBackgroundPointsTable;
use DrdPlus\Tests\Tables\TableTest;
use Granam\Integer\PositiveIntegerObject;

class SkillsByBackgroundPointsTableTest extends TableTest
{

    /**
     * @test
     */
    public function I_can_get_header()
    {
        $backgroundSkillsTable = new SkillsByBackgroundPointsTable();
        self::assertEquals(
            [
                ['', 'commoner', 'commoner', 'commoner', 'fighter', 'fighter', 'fighter', 'thief', 'thief', 'thief',
                    'ranger', 'ranger', 'ranger', 'wizard', 'wizard', 'wizard', 'theurgist', 'theurgist', 'theurgist',
                    'priest', 'priest', 'priest',
                ],
                ['background_points', 'physical', 'psychical', 'combined', 'physical', 'psychical', 'combined',
                    'physical', 'psychical', 'combined', 'physical', 'psychical', 'combined', 'physical', 'psychical',
                    'combined', 'physical', 'psychical', 'combined', 'physical', 'psychical', 'combined',
                ],
            ],
            $backgroundSkillsTable->getHeader()
        );
    }

    /**
     * @test
     * @dataProvider provideSkillPointToProfession
     * @param int $backgroundSkillPoints
     * @param string $professionCode
     * @param string $skillType
     * @param int $expectedSkillPoints
     */
    public function I_can_get_skills_for_each_profession(
        $backgroundSkillPoints,
        $professionCode,
        $skillType,
        $expectedSkillPoints
    )
    {
        $backgroundSkillsTable = new SkillsByBackgroundPointsTable();
        self::assertSame(
            $expectedSkillPoints,
            $backgroundSkillsTable->getSkillPoints(
                new PositiveIntegerObject($backgroundSkillPoints),
                ProfessionCode::getIt($professionCode),
                SkillTypeCode::getIt($skillType)
            )
        );

        $getGroupSkillPoints = 'get' . ucfirst($skillType) . 'SkillPoints';
        $groupSkillPoints = $backgroundSkillsTable->$getGroupSkillPoints(
            new PositiveIntegerObject($backgroundSkillPoints),
            ProfessionCode::getIt($professionCode)
        );
        self::assertSame($expectedSkillPoints, $groupSkillPoints);

        $getProfessionGroupSkillPoints = 'get' . ucfirst($professionCode) . ucfirst($skillType) . 'SkillPoints';
        $professionGroupSkillPoints = $backgroundSkillsTable->$getProfessionGroupSkillPoints(
            new PositiveIntegerObject($backgroundSkillPoints)
        );
        self::assertSame($expectedSkillPoints, $professionGroupSkillPoints);
    }

    public function provideSkillPointToProfession()
    {
        $combinations = [];
        $rowIndex = 0;
        for ($backgroundSkillPoint = 0; $backgroundSkillPoint <= 8; $backgroundSkillPoint++) {
            $columnIndex = 0;
            foreach (ProfessionCode::getPossibleValues() as $professionCode) {
                foreach (SkillTypeCode::getPossibleValues() as $skillTypeCode) {
                    $combinations[] = [
                        $backgroundSkillPoint,
                        $professionCode,
                        $skillTypeCode,
                        $this->getExpectedSkillPoints($rowIndex, $columnIndex),
                    ];
                    $columnIndex++;
                }
            }
            $rowIndex++;
        }

        return $combinations;
    }

    private static $expectedSkillPoints = [
        [1, 1, 1, 2, 0, 1, 1, 1, 1, 2, 0, 1, 0, 3, 0, 0, 2, 1, 0, 1, 2,],
        [1, 1, 1, 3, 0, 1, 2, 1, 1, 2, 0, 2, 1, 3, 0, 0, 3, 1, 0, 2, 2,],
        [1, 1, 1, 4, 0, 1, 2, 1, 2, 3, 0, 2, 1, 4, 0, 0, 4, 1, 1, 2, 2,],
        [2, 2, 2, 4, 1, 2, 3, 2, 2, 3, 1, 3, 2, 4, 1, 1, 4, 2, 1, 3, 3,],
        [3, 3, 3, 5, 1, 3, 4, 2, 3, 4, 1, 4, 2, 5, 2, 1, 5, 3, 2, 3, 4,],
        [3, 3, 3, 6, 2, 3, 5, 2, 4, 5, 1, 5, 3, 6, 2, 2, 6, 3, 2, 4, 5,],
        [4, 4, 4, 8, 2, 4, 6, 3, 5, 6, 2, 6, 4, 7, 3, 2, 8, 4, 3, 5, 6,],
        [5, 5, 5, 10, 3, 5, 8, 4, 6, 8, 3, 7, 5, 9, 4, 3, 10, 5, 4, 7, 7,],
        [6, 6, 6, 12, 4, 6, 9, 6, 7, 10, 4, 8, 6, 11, 5, 4, 12, 6, 5, 9, 8,],
    ];

    private function getExpectedSkillPoints($rowIndex, $columnIndex)
    {
        if (!array_key_exists($rowIndex, self::$expectedSkillPoints)) {
            throw new \LogicException(
                "Required row index {$rowIndex} does not exists, max is " . max(array_keys(self::$expectedSkillPoints))
            );
        }
        if (!array_key_exists($columnIndex, self::$expectedSkillPoints[$rowIndex])) {
            throw new \LogicException(
                "Required column index {$columnIndex} does not exists, max is "
                . max(array_keys(self::$expectedSkillPoints[$rowIndex]))
            );
        }

        return self::$expectedSkillPoints[$rowIndex][$columnIndex];
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\History\Exceptions\UnexpectedBackgroundPoints
     * @expectedExceptionMessageRegExp ~9~
     */
    public function I_can_not_get_skill_points_for_invalid_background_points()
    {
        (new SkillsByBackgroundPointsTable())->getSkillPoints(
            new PositiveIntegerObject(9),
            ProfessionCode::getIt(ProfessionCode::FIGHTER),
            SkillTypeCode::getIt(SkillTypeCode::COMBINED)
        );
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\History\Exceptions\UnexpectedProfessionAndSkillTypeCombination
     * @expectedExceptionMessageRegExp ~baker~
     */
    public function I_can_not_get_skill_points_for_unknown_profession()
    {
        (new SkillsByBackgroundPointsTable())->getSkillPoints(
            new PositiveIntegerObject(6),
            $this->createProfessionCode('baker'),
            SkillTypeCode::getIt(SkillTypeCode::COMBINED)
        );
    }

    /**
     * @param string $value
     * @return ProfessionCode|\Mockery\MockInterface
     */
    private function createProfessionCode($value)
    {
        $professionCode = $this->mockery(ProfessionCode::class);
        $professionCode->shouldReceive('getValue')
            ->andReturn($value);
        $professionCode->shouldReceive('__toString')
            ->andReturn((string)$value);

        return $professionCode;
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\History\Exceptions\UnexpectedProfessionAndSkillTypeCombination
     * @expectedExceptionMessageRegExp ~godlike~
     */
    public function I_can_not_get_skill_points_for_unknown_skill_type()
    {
        (new SkillsByBackgroundPointsTable())->getSkillPoints(
            new PositiveIntegerObject(7),
            ProfessionCode::getIt(ProfessionCode::PRIEST),
            $this->createSkillType('godlike')
        );
    }

    /**
     * @param string $value
     * @return SkillTypeCode|\Mockery\MockInterface
     */
    private function createSkillType($value)
    {
        $skillTypeCode = $this->mockery(SkillTypeCode::class);
        $skillTypeCode->shouldReceive('getValue')
            ->andReturn($value);
        $skillTypeCode->shouldReceive('__toString')
            ->andReturn((string)$value);

        return $skillTypeCode;
    }
}