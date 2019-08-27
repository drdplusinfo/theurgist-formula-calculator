<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Measurements\Experiences;

use DrdPlus\Tables\Measurements\Experiences\Experiences;
use DrdPlus\Tables\Measurements\Experiences\ExperiencesTable;
use DrdPlus\Tables\Measurements\Experiences\Level;
use DrdPlus\Tables\Measurements\Measurement;
use DrdPlus\Tables\Table;
use DrdPlus\Tests\Tables\Measurements\AbstractTestOfMeasurement;
use Mockery\MockInterface;

class ExperiencesTest extends AbstractTestOfMeasurement
{

    protected function createSutWithTable(string $sutClass, int $amount, string $unit, Table $table): Measurement
    {
        return new $sutClass($amount, $table, $unit);
    }

    /**
     * @test
     */
    public function I_can_get_experiences()
    {
        $experiences = new Experiences(
            $value = 456,
            $this->getExperiencesTable(),
            Experiences::EXPERIENCES
        );
        self::assertSame($value, $experiences->getValue());
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
    public function I_can_get_level()
    {
        $experiences = new Experiences(
            $value = 111,
            $experiencesTable = $this->getExperiencesTable(),
            Experiences::EXPERIENCES
        );
        $experiencesTable->shouldReceive('toLevel')
            ->atLeast()->once()
            ->with($experiences)
            ->andReturn($level = $this->createLevel());
        self::assertSame($level, $experiences->getLevel());
    }

    /**
     * @return Level|MockInterface
     */
    private function createLevel(): Level
    {
        return $this->mockery(Level::class);
    }

    /**
     * @test
     */
    public function I_can_get_total_level()
    {
        $experiences = new Experiences(
            $value = 123,
            $experiencesTable = $this->getExperiencesTable(),
            Experiences::EXPERIENCES
        );
        $experiencesTable->shouldReceive('toTotalLevel')
            ->atLeast()->once()
            ->with($experiences)
            ->andReturn($level = $this->createLevel());
        self::assertSame($level, $experiences->getTotalLevel());
    }

}