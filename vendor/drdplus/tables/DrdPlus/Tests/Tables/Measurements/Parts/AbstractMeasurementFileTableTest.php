<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Measurements\Partials;

use DrdPlus\Tables\Measurements\Bonus;
use DrdPlus\Tables\Measurements\MeasurementWithBonus;
use DrdPlus\Tables\Measurements\Partials\AbstractBonus;
use DrdPlus\Tables\Measurements\Partials\AbstractMeasurementFileTable;
use DrdPlus\Tables\Measurements\Partials\Exceptions\LoadingDataFailed;
use DrdPlus\Tables\Measurements\Tools\EvaluatorInterface;
use DrdPlus\Tables\Partials\AbstractTable;
use Granam\Tests\Tools\TestWithMockery;

class AbstractMeasurementFileTableTest extends TestWithMockery
{

    /**
     * @var string
     */
    private $tempFilename;

    protected function tearDown(): void
    {
        parent::tearDown();
        if ($this->tempFilename && file_exists($this->tempFilename)) {
            unlink($this->tempFilename);
        }
    }

    /**
     * @test
     */
    public function I_can_not_create_table_without_source_file()
    {
        $this->expectException(\DrdPlus\Tables\Measurements\Exceptions\FileCanNotBeRead::class);
        $originalErrorReporting = error_reporting();
        try {
            error_reporting($originalErrorReporting ^ E_WARNING);
            $table = TestOfAbstractTable::getIt('non-existing-file');
            $table->getIndexedValues();
        } catch (LoadingDataFailed $loadingDataFailed) {
            error_reporting($originalErrorReporting);
            $lastError = error_get_last();
            self::assertSame(E_WARNING, $lastError['type']);
            throw $loadingDataFailed->getPrevious();
        }
    }

    /**
     * @test
     */
    public function I_can_not_create_table_with_empty_source_file()
    {
        $this->expectException(\DrdPlus\Tables\Measurements\Exceptions\FileIsEmpty::class);
        $table = TestOfAbstractTable::getIt($this->createTempFilename());
        try {
            $table->getIndexedValues();
        } catch (LoadingDataFailed $loadingDataFailed) {
            throw $loadingDataFailed->getPrevious();
        }
    }

    private function createTempFilename()
    {
        return $this->tempFilename = tempnam(sys_get_temp_dir(), 'foo');
    }

    /**
     * @test
     */
    public function I_can_not_create_table_with_corrupted_data()
    {
        $this->expectException(\DrdPlus\Tables\Measurements\Exceptions\DataFromFileAreCorrupted::class);
        $filename = $this->createTempFilename();
        file_put_contents($filename, 'bar');
        $table = TestOfAbstractTable::getIt($filename);
        try {
            $table->getIndexedValues();
        } catch (LoadingDataFailed $loadingDataFailed) {
            throw $loadingDataFailed->getPrevious();
        }
    }

    /**
     * @test
     */
    public function I_can_not_create_table_without_data()
    {
        $this->expectException(\DrdPlus\Tables\Measurements\Exceptions\DataRowsAreMissingInFile::class);
        $filename = $this->createTempFilename();
        file_put_contents($filename, 'bonus');
        $table = TestOfAbstractTable::getIt($filename);
        try {
            $table->getIndexedValues();
        } catch (LoadingDataFailed $loadingDataFailed) {
            throw $loadingDataFailed->getPrevious();
        }
    }

    /**
     * @test
     */
    public function I_can_not_convert_bonus_to_unknown_unit()
    {
        $this->expectException(\DrdPlus\Tables\Measurements\Exceptions\UnknownUnit::class);
        $filename = $this->createTempFilename();
        $bonus = new BonusForTestOfAbstractTable(123);
        file_put_contents($filename, "bonus\n$bonus");
        $table = TestOfAbstractTable::getIt($filename);
        $table->toMeasurement($bonus, 'non-existing-unit');
    }

    /**
     * @test
     */
    public function I_can_not_convert_bonus_to_invalid_value_change()
    {
        $this->expectException(\DrdPlus\Tables\Measurements\Exceptions\UnexpectedChanceNotation::class);
        $filename = $this->createTempFilename();
        $bonus = new BonusForTestOfAbstractTable(123);
        $unit = 'bar';
        $invalidChance = '1/1';
        file_put_contents($filename, "bonus,$unit\n$bonus,$invalidChance");
        $table = TestOfAbstractTable::getIt($filename, [$unit]);
        $table->toMeasurement($bonus, $unit);
    }

    /**
     * @test
     */
    public function I_am_stopped_by_an_exception_if_can_not_convert_bonus_to_value()
    {
        $this->expectException(\DrdPlus\Tables\Measurements\Partials\Exceptions\RequestedDataOutOfTableRange::class);
        $filename = $this->createTempFilename();
        $bonus = new BonusForTestOfAbstractTable(123);
        $unit = 'bar';
        file_put_contents($filename, "bonus,$unit\n$bonus,");
        $table = TestOfAbstractTable::getIt($filename, [$unit]);
        $table->toMeasurement($bonus, $unit);
    }

    /**
     * @test
     */
    public function I_can_not_use_same_bonus_for_more_values()
    {
        $this->expectException(\DrdPlus\Tables\Measurements\Exceptions\BonusAlreadyPaired::class);
        $filename = $this->createTempFilename();
        $bonus = 123;
        $unit = 'bar';
        file_put_contents($filename, "bonus,$unit\n$bonus,1\n$bonus,2");
        $table = TestOfAbstractTable::getIt($filename, [$unit]);
        try {
            $table->getIndexedValues();
        } catch (LoadingDataFailed $loadingDataFailed) {
            throw $loadingDataFailed->getPrevious();
        }
    }

    /**
     * @test
     */
    public function I_am_stopped_if_bonus_contains_non_numeric_value()
    {
        $this->expectException(\DrdPlus\Tables\Measurements\Partials\Exceptions\LoadingDataFailed::class);
        $filename = $this->createTempFilename();
        file_put_contents($filename, "bonus,foo\nSonOfSun,123456");
        $table = TestOfAbstractTable::getIt($filename, ['foo']);
        $table->getIndexedValues();
    }

    /**
     * @test
     * @see PPH page 73, right column, first block
     */
    public function I_can_use_same_value_for_more_bonuses_and_get_lowest_bonus_by_closest_value()
    {
        $filename = $this->createTempFilename();
        $unit = 'bar';
        $lowerValue = 123;
        $higherValue = 321;
        $expectedValues = [
            456 => [$unit => (float)$lowerValue],
            567 => [$unit => (float)$lowerValue],
            678 => [$unit => (float)$higherValue],
            789 => [$unit => (float)$higherValue],
        ];
        $rows = ["bonus,$unit"];
        foreach ($expectedValues as $bonus => $expectedValue) {
            $rows[] = "$bonus," . $expectedValue[$unit]; // lower or higher value
        }
        file_put_contents($filename, implode("\n", $rows));
        $table = TestOfAbstractTable::getIt($filename, [$unit]);
        self::assertEquals($expectedValues, $table->getIndexedValues());
        $middleValue = 234;
        self::assertGreaterThan($lowerValue, $middleValue);
        self::assertLessThan($higherValue, $middleValue);

        $bonus = $table->toBonus($unit, $middleValue);

        self::assertLessThan(
            $middleValue - $lowerValue,
            $higherValue - $middleValue,
            'Expected middle value to be closer to the higher than lower value'
        );
        $closerValue = (float)$higherValue;
        $bonusesOfClosestValue = array_filter(array_map(
            function ($bonus, array $value) use ($closerValue) {
                if (current($value) === $closerValue) {
                    return $bonus; // this bonus will be used for choose of lowest
                }

                return false; // will be filtered out, see wrapping array_filter
            },
            array_keys($expectedValues), $expectedValues
        ));

        self::assertSame(
            min($bonusesOfClosestValue), // the lowest bonus ('first' in words of PPH)
            $bonus->getValue()
        );
    }

    /**
     * @test
     */
    public function I_can_get_measurement_with_auto_chosen_unit_by_bonus()
    {
        $filename = $this->createTempFilename();
        $bonusValue1 = 123;
        $bonusValue2 = 456;
        $bonusValue3 = 789;
        $unit1 = 'bar';
        $unit2 = 'baz';
        $values1 = [1, 2];
        $values2 = [10, 20, 30];
        file_put_contents(
            $filename,
            "bonus,$unit1,$unit2
            $bonusValue1,{$values1[0]},{$values2[1]}
            $bonusValue2,{$values1[1]},{$values2[1]}
            $bonusValue3,,{$values2[2]}"
        );
        $table = TestOfAbstractTable::getIt($filename, [$unit1, $unit2]);
        $measurementFromSecondRow = $table->toMeasurement(
            new BonusForTestOfAbstractTable($bonusValue2),
            null /* auto-select unit*/
        );
        self::assertSame(2.0, $measurementFromSecondRow->getValue());
        self::assertSame($unit1, $measurementFromSecondRow->getUnit());
        $measurementFromThirdRow = $table->toMeasurement(
            new BonusForTestOfAbstractTable($bonusValue3),
            null /* auto-select unit*/
        );
        self::assertSame(30.0, $measurementFromThirdRow->getValue());
        self::assertSame($unit2, $measurementFromThirdRow->getUnit());
    }

    /**
     * @test
     */
    public function I_got_chance_evaluated_properly()
    {
        $filename = $this->createTempFilename();
        $chances = range(0, 6);
        $unit = 'bar';
        $bonusValues = [];
        $rows = [];
        foreach ($chances as $chance) {
            $bonusValues[$chance] = $bonusValue = $this->createSomeBonusValue($chance);
            $rows[] = "$bonusValue,$chance/6";
        }
        file_put_contents($filename, "bonus,$unit\n" . implode("\n", $rows));
        $valuesToEvaluate = [];
        $table = TestOfAbstractTable::getIt($filename, [$unit], $this->createOneToOneEvaluator($valuesToEvaluate));
        foreach ($bonusValues as $chance => $bonusValue) {
            $bonus = new BonusForTestOfAbstractTable($bonusValue);
            self::assertEquals(
            /** @see \DrdPlus\Tests\Tables\Measurements\TestOfAbstractTable::convertToMeasurement */
                new JustSomeMeasurementWithBonus($chance, $unit),
                $table->toMeasurement($bonus, $unit)
            );
        }
        self::assertSame($chances, $valuesToEvaluate);
    }

    private function createSomeBonusValue($referenceNumber)
    {
        return $referenceNumber + 3; // just a simple linear value shift
    }

    private function createOneToOneEvaluator(array &$valuesToEvaluate)
    {
        $evaluator = $this->mockery(EvaluatorInterface::class);
        $evaluator->shouldReceive('evaluate')
            ->atLeast()->once()
            ->andReturnUsing(function ($toEvaluate) use (&$valuesToEvaluate) {
                $valuesToEvaluate[] = $toEvaluate;

                return $toEvaluate;
            });

        return $evaluator;
    }

    /**
     * @test
     */
    public function I_can_get_simplified_header_with_more_row_header_rows_then_column_header()
    {
        $withLessColumnHeaderRowsThenRowHeader = new WithLessColumnHeaderRowsThenRowHeader();
        self::assertEquals(
            [
                ['foo', ''],
                ['bar', 'baz'],
            ],
            $withLessColumnHeaderRowsThenRowHeader->getHeader()
        );
    }

}

/** inner */
class TestOfAbstractTable extends AbstractMeasurementFileTable
{
    /**
     * @var string
     */
    private $dataFileName;
    private $dataHeader;

    /**
     * @param $dataFileName
     * @param array $units
     * @param EvaluatorInterface|\Mockery\MockInterface|null $evaluator
     * @return static
     */
    public static function getIt(string $dataFileName, array $units = [], EvaluatorInterface $evaluator = null)
    {
        $evaluator = $evaluator ?: \Mockery::mock(EvaluatorInterface::class);

        /** @var EvaluatorInterface $evaluator */

        return new static($evaluator, $dataFileName, $units);
    }

    /**
     * @param EvaluatorInterface $evaluator
     * @param bool $dataFileName
     * @param array $units
     */
    public function __construct(EvaluatorInterface $evaluator, $dataFileName = false, $units = [])
    {
        $this->dataFileName = $dataFileName;
        $this->dataHeader = $units;
        parent::__construct($evaluator);
    }

    public function getValues(): array
    {
        return [];
    }

    public function getHeader(): array
    {
        return [];
    }

    /**
     * @return \string[]
     */
    protected function getExpectedDataHeader(): array
    {
        return $this->dataHeader;
    }

    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return $this->dataFileName;
    }

    /**
     * @param float $value
     * @param string $unit
     * @return MeasurementWithBonus
     */
    protected function convertToMeasurement(float $value, string $unit): MeasurementWithBonus
    {
        return new JustSomeMeasurementWithBonus($value, $unit);
    }

    /**
     * @param int $bonusValue
     * @return AbstractBonus
     */
    protected function createBonus(int $bonusValue): AbstractBonus
    {
        return new BonusForTestOfAbstractTable($bonusValue);
    }

    /**
     * Just making it public.
     *
     * @param AbstractBonus $bonus
     * @param string|null $unit
     * @return MeasurementWithBonus
     */
    public function toMeasurement(AbstractBonus $bonus, string $unit = null): MeasurementWithBonus
    {
        return parent::toMeasurement($bonus, $unit);
    }

    /**
     * @param string $unit
     * @param mixed $value
     * @return AbstractBonus
     */
    public function toBonus(string $unit, $value): AbstractBonus
    {
        /** @var \Mockery\MockInterface|MeasurementWithBonus $measurement */
        $measurement = \Mockery::mock(MeasurementWithBonus::class);
        $measurement->shouldReceive('getUnit')
            ->andReturn($unit);
        $measurement->shouldReceive('getValue')
            ->andReturn($value);

        return parent::measurementToBonus($measurement);
    }

}

/**inner */
class BonusForTestOfAbstractTable extends AbstractBonus
{
    public function __construct($value)
    {
        parent::__construct($value);
    }
}

class WithLessColumnHeaderRowsThenRowHeader extends AbstractTable
{
    protected function getRowsHeader(): array
    {
        return [
            ['foo', 'bar'],
        ];
    }

    protected function getColumnsHeader(): array
    {
        return ['baz'];
    }

    public function getIndexedValues(): array
    {
        throw new \LogicException('Should not be called at all');
    }

}

class JustSomeMeasurementWithBonus implements MeasurementWithBonus
{
    /** @var float */
    private $value;
    /** @var string */
    private $unit;

    public function __construct(float $value, string $unit)
    {
        $this->value = $value;
        $this->unit = $unit;
    }

    public function getUnit(): string
    {
        return $this->unit;
    }

    public function getPossibleUnits(): array
    {
        return [$this->unit];
    }

    public function getBonus(): Bonus
    {
        return new BonusForTestOfAbstractTable(0);
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function __toString()
    {
        return '';
    }

}