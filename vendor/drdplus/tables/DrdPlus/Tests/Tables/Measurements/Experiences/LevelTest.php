<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Measurements\Experiences;

use DrdPlus\Tables\Measurements\Bonus;
use DrdPlus\Tables\Measurements\Experiences\Experiences;
use DrdPlus\Tables\Measurements\Experiences\ExperiencesTable;
use DrdPlus\Tables\Measurements\Experiences\Level;
use DrdPlus\Tables\Measurements\Wounds\WoundsTable;
use DrdPlus\Tables\Partials\AbstractTable;
use DrdPlus\Tests\Tables\Measurements\AbstractTestOfBonus;
use Mockery\MockInterface;

class LevelTest extends AbstractTestOfBonus
{
    /**
     * @test
     */
    public function I_can_create_bonus(): void
    {
        $sut = $this->createSut($value = 20);
        self::assertInstanceOf(Bonus::class, $sut);
        self::assertSame($value, $sut->getValue());
    }

    protected function getTableInstance(): AbstractTable
    {
        return new ExperiencesTable(new WoundsTable());
    }

    protected function getNameOfMeasurementGetter(): string
    {
        return 'getExperiences';
    }

    protected function getMeasurementClass(): string
    {
        return Experiences::class;
    }

    /**
     * @test
     */
    public function I_can_get_level_value(): void
    {
        $level = new Level($levelValue = 20, $this->getExperiencesTable());
        self::assertSame($levelValue, $level->getValue());
    }

    protected function findTable()
    {
        return $this->getExperiencesTable();
    }

    /**
     * @return ExperiencesTable|\Mockery\MockInterface
     */
    private function getExperiencesTable()
    {
        return $this->mockery(ExperiencesTable::class);
    }

    /**
     * @test
     */
    public function I_can_get_experiences()
    {
        $level = new Level(11, $experiencesTable = $this->getExperiencesTable());
        $experiencesTable->shouldReceive('toExperiences')
            ->atLeast()->once()
            ->with($level)
            ->andReturn($experiences = $this->createExperiences());
        self::assertSame($experiences, $level->getExperiences());

        $level = new Level(5, $experiencesTable = $this->getExperiencesTable());
        $experiencesTable->shouldReceive('toTotalExperiences')
            ->atLeast()->once()
            ->with($level)
            ->andReturn($totalExperiences = $this->createExperiences());
        self::assertSame($totalExperiences, $level->getTotalExperiences());
    }

    /**
     * @return Experiences|MockInterface
     */
    private function createExperiences(): Experiences
    {
        return $this->mockery(Experiences::class);
    }

    /**
     * @test
     */
    public function I_cannot_create_higher_level_than_cap()
    {
        $this->expectException(\DrdPlus\Tables\Measurements\Experiences\Exceptions\MaxLevelOverflow::class);
        new Level(21, $this->getExperiencesTable());
    }

    /**
     * @test
     */
    public function I_cannot_create_negative_level()
    {
        $this->expectException(\DrdPlus\Tables\Measurements\Experiences\Exceptions\MinLevelUnderflow::class);
        new Level(-1, $this->getExperiencesTable());
    }

    /**
     * @test
     */
    public function I_can_create_zero_level()
    {
        $zeroLevel = new Level(0, $this->getExperiencesTable());
        self::assertSame(0, $zeroLevel->getValue());
    }
}
