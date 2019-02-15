<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Measurements;

use DrdPlus\Tables\Measurements\Bonus;
use DrdPlus\Tables\Measurements\MeasurementWithBonus;
use DrdPlus\Tables\Measurements\Partials\AbstractBonus;
use DrdPlus\Tables\Partials\AbstractTable;
use DrdPlus\Tables\Tables;
use Granam\Integer\IntegerObject;
use Granam\String\StringTools;
use Granam\Tests\Tools\TestWithMockery;
use Mockery\MockInterface;

abstract class AbstractTestOfBonus extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_create_bonus(): void
    {
        $sutByFactoryMethod = $this->getSutByFactoryMethod(987);
        self::assertInstanceOf(Bonus::class, $sutByFactoryMethod);
        self::assertSame(987, $sutByFactoryMethod->getValue());

        $sut = $this->createSut(123);
        self::assertInstanceOf(Bonus::class, $sut);
        self::assertSame(123, $sut->getValue());

        $sut = $this->createSut('456');
        self::assertSame(456, $sut->getValue());

        $sut = $this->createSut(new IntegerObject(789));
        self::assertSame(789, $sut->getValue());
    }

    /**
     * @param $value
     * @return Bonus
     */
    protected function getSutByFactoryMethod($value): Bonus
    {
        $bonusClass = $this->getBonusClass();
        /** @noinspection PhpUnhandledExceptionInspection */
        $reflectionClass = new \ReflectionClass($bonusClass);
        self::assertTrue($reflectionClass->hasMethod('getIt'), "$bonusClass does not have factory method 'getIt'");
        $getIt = $reflectionClass->getMethod('getIt');
        self::assertTrue($getIt->isStatic(), "{$bonusClass}->getIt() factory method should be static");
        self::assertTrue($getIt->isPublic(), "{$bonusClass}->getIt() factory method should be public");

        return $bonusClass::getIt($value, $this->getTablesWithTable());
    }

    /**
     * @return Tables|MockInterface
     */
    protected function getTablesWithTable(): Tables
    {
        $tables = $this->mockery(Tables::class);
        $tables->shouldReceive(StringTools::assembleGetterForName(StringTools::getClassBaseName($this->getTableClass())))
            ->andReturn($this->getTableInstance());

        return $tables;
    }

    /**
     * @param mixed $value
     * @return Bonus
     */
    protected function createSut($value): Bonus
    {
        $bonusClass = $this->getBonusClass();

        return new $bonusClass($value, $this->getTableInstance());
    }

    /**
     * @return null|string|AbstractBonus
     */
    protected function getBonusClass(): string
    {
        return \preg_replace('~[\\\]Tests(.+)Test$~', '$1', static::class);
    }

    protected function getTableInstance(): AbstractTable
    {
        $tableClass = $this->getTableClass();

        return new $tableClass();
    }

    protected function getTableClass(): string
    {
        return \preg_replace('~Bonus$~', 'Table', $this->getBonusClass());
    }

    /**
     * @test
     */
    public function I_can_get_measurement_from_bonus(): void
    {
        $sut = $this->createSut($bonusValue = 12);
        self::assertSame($bonusValue, $sut->getValue());
        $getMeasurement = $this->getNameOfMeasurementGetter();
        $measurement = $sut->$getMeasurement();
        /** @var MeasurementWithBonus $measurement */
        self::assertInstanceOf($this->getMeasurementClass(), $measurement);
        self::assertInstanceOf($this->getBonusClass(), $measurement->getBonus());
        // the bonus-to-measurement-to-bonus can be lossy transformation
        self::assertTrue(
            $measurement->getBonus()->getValue() === $bonusValue
            || $measurement->getBonus()->getValue() === $bonusValue - 1
            || $measurement->getBonus()->getValue() === $bonusValue + 1
        );
    }

    protected function getNameOfMeasurementGetter(): string
    {
        $measurementClass = $this->getMeasurementClass();
        \preg_match('~\\\(?<basename>\w+)$~', $measurementClass, $matches);
        $measurementBasename = $matches['basename'];

        return "get$measurementBasename";
    }

    protected function getMeasurementClass(): string
    {
        $bonusClassName = $this->getBonusClass();

        return \preg_replace('~Bonus$~', '', $bonusClassName);
    }
}
