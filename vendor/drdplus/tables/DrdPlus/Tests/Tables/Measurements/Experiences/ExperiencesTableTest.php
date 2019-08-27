<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Measurements\Experiences;

use DrdPlus\Tables\Measurements\Experiences\Experiences;
use DrdPlus\Tables\Measurements\Experiences\ExperiencesTable;
use DrdPlus\Tables\Measurements\Experiences\Level;
use DrdPlus\Tables\Measurements\Wounds\WoundsTable;
use DrdPlus\Tests\Tables\TableTest;

class ExperiencesTableTest extends TableTest
{
    /**
     * @test
     */
    public function I_can_get_header()
    {
        $experiencesTable = new ExperiencesTable($woundsTable = new WoundsTable());

        self::assertEquals($woundsTable->getHeader(), $experiencesTable->getHeader());
    }

    /**
     * @test
     */
    public function I_get_values_same_as_from_wounds_table()
    {
        $experiencesTable = new ExperiencesTable($woundsTable = new WoundsTable());

        self::assertEquals($woundsTable->getValues(), $experiencesTable->getValues());
        self::assertEquals($woundsTable->getIndexedValues(), $experiencesTable->getIndexedValues());
    }

    /**
     * @test
     */
    public function I_can_convert_experiences_to_level()
    {
        $experiencesTable = new ExperiencesTable($woundsTable = new WoundsTable());

        $experiencesToLevel = new Experiences(123, $experiencesTable);
        $level = $experiencesTable->toLevel($experiencesToLevel);
        self::assertInstanceOf(Level::class, $level);
        self::assertSame(17, $level->getValue());

        $experiencesToTotalLevel = new Experiences(123, $experiencesTable);
        $totalLevel = $experiencesTable->toTotalLevel($experiencesToTotalLevel);
        self::assertInstanceOf(Level::class, $level);
        self::assertSame(5, $totalLevel->getValue());
    }

    /**
     * @test
     */
    public function I_can_convert_level_to_experiences()
    {
        $experiencesTable = new ExperiencesTable($woundsTable = new WoundsTable());
        $level = new Level(11, $experiencesTable);

        self::assertSame(63, $experiencesTable->toExperiences($level)->getValue());
        self::assertSame(397, $experiencesTable->toTotalExperiences($level)->getValue());
    }

    /**
     * @test
     */
    public function I_can_convert_first_level_to_experiences()
    {
        $experiencesTable = new ExperiencesTable($woundsTable = new WoundsTable());
        $firstLevel = new Level(1, $experiencesTable);
        self::assertSame(0, $experiencesTable->toExperiences($firstLevel)->getValue());
        self::assertSame(0, $experiencesTable->toTotalExperiences($firstLevel)->getValue());
    }

    /**
     * @test
     */
    public function I_can_convert_max_level_to_experiences()
    {
        $experiencesTable = new ExperiencesTable($woundsTable = new WoundsTable());
        $lastLevel = new Level($levelValue = 20, $experiencesTable);
        self::assertSame(180, $experiencesTable->toExperiences($lastLevel)->getValue());
        self::assertSame(1447, $experiencesTable->toTotalExperiences($lastLevel)->getValue());
    }

    /**
     * @test
     */
    public function I_can_convert_zero_experiences_to_first_level()
    {
        $experiencesTable = new ExperiencesTable(new WoundsTable());
        $zeroExperiences = new Experiences($experiencesValue = 0, $experiencesTable);

        $levelOfMainProfession = $experiencesTable->toLevel($zeroExperiences);
        self::assertSame(1, $levelOfMainProfession->getValue());
        $totalLevelOfMainProfession = $experiencesTable->toTotalLevel($zeroExperiences);
        self::assertSame(1, $totalLevelOfMainProfession->getValue());
    }

    /**
     * @test
     */
    public function I_can_create_first_level_from_max_experiences_for_it_but_get_zero_back()
    {
        $experiencesTable = new ExperiencesTable(new WoundsTable());
        $experiencesForFirstLevel = new Experiences($experiencesValue = 21, $experiencesTable);

        $levelOfMainProfession = $experiencesTable->toTotalLevel($experiencesForFirstLevel);
        self::assertSame(1, $levelOfMainProfession->getValue());
        self::assertSame(0, $levelOfMainProfession->getExperiences()->getValue());
        self::assertSame(0, $levelOfMainProfession->getTotalExperiences()->getValue());

        $minimalExperiencesForSecondLevel = new Experiences(
            $experiencesValue + 2, // need 22 experiences for second level
            $experiencesTable
        );
        $shouldBeSecondLevel = $minimalExperiencesForSecondLevel->getLevel();
        self::assertSame(2, $shouldBeSecondLevel->getValue());
        self::assertSame(
            $experiencesValue + 1, // lossy conversion, needed 22 XP for second level but got 21 back
            $shouldBeSecondLevel->getExperiences()->getValue()
        );
        self::assertSame($experiencesValue + 1, $shouldBeSecondLevel->getTotalExperiences()->getValue());
    }
}
