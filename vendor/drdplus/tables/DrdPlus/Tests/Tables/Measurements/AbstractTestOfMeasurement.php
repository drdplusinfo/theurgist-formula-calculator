<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Measurements;

use DrdPlus\Tables\Measurements\Measurement;
use DrdPlus\Tables\Partials\AbstractTable;
use DrdPlus\Tables\Table;
use Granam\Tests\Tools\TestWithMockery;

abstract class AbstractTestOfMeasurement extends TestWithMockery
{

    /**
     * @test
     */
    public function I_can_get_value_and_unit(): void
    {
        $measurement = $this->createSut($amount = 123);
        self::assertEquals($amount, $measurement->getValue());
        self::assertSame($this->getDefaultUnit(), $measurement->getUnit());
    }

    /**
     * @param int $amount
     * @return Measurement
     */
    protected function createSut(int $amount): Measurement
    {
        $sutClass = self::getSutClass();
        $unit = $this->getDefaultUnit();
        $table = $this->findTable();

        if (!$table) {
            return new $sutClass($amount, $unit);
        }

        return $this->createSutWithTable($sutClass, $amount, $unit, $table);
    }

    /**
     * @param string $sutClass
     * @param int $amount
     * @param string $unit
     * @param Table $table
     * @return Measurement
     */
    protected function createSutWithTable(string $sutClass, int $amount, string $unit, Table $table): Measurement
    {
        return new $sutClass($amount, $unit, $table);
    }

    protected function getDefaultUnit(): string
    {
        return \constant($this->getConstantAbsoluteName());
    }

    protected function getConstantAbsoluteName(): string
    {
        $constantBaseName = $this->getConstantBaseName();
        $class = self::getSutClass();

        return "$class::$constantBaseName";
    }

    protected function getConstantBaseName(): string
    {
        $classBaseName = $this->parseClassBaseName(self::getSutClass());
        $underscored = \ltrim(\preg_replace('~([A-Z])~', '_$1', $classBaseName), '_');

        return \strtoupper($underscored);
    }

    /**
     * @param string $className
     * @return string
     */
    protected function parseClassBaseName(string $className): string
    {
        return \preg_replace('~.+\\\(\w+)$~', '$1', $className);
    }

    /**
     * @return array|string[]
     */
    protected function getAllUnits(): array
    {
        return [$this->getDefaultUnit()];
    }

    /**
     * @return \Mockery\MockInterface|AbstractTable|null
     */
    protected function findTable()
    {
        $tableClass = $this->getTableClass();
        if (!$tableClass) {
            return null;
        }

        return $this->mockery($tableClass);
    }

    /**
     * @return string|null
     */
    protected function getTableClass(): ? string
    {
        $measurementClass = self::getSutClass();
        $tableClass = "{$measurementClass}Table";

        if (!\class_exists($tableClass)) {
            return null;
        }

        return $tableClass;
    }
}