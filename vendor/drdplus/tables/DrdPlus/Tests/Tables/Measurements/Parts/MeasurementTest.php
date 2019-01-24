<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\Measurements\Partials;

use DrdPlus\Tables\Measurements\Partials\AbstractMeasurement;
use Granam\Tests\Tools\TestWithMockery;

class MeasurementTest extends TestWithMockery
{

    /**
     * @test
     */
    public function I_can_get_value_and_unit()
    {
        $measurement = new DeAbstractedMeasurement(
            $amount = 123,
            $unit = DeAbstractedMeasurement::POSSIBLE_UNIT
        );
        self::assertSame((float)$amount, $measurement->getValue());
        self::assertSame($unit, $measurement->getUnit());
    }

    /**
     * @test
     */
    public function I_can_get_measurement_value_and_unit_by_to_string_conversion()
    {
        $measurement = new DeAbstractedMeasurement($value = 123, $unit = DeAbstractedMeasurement::POSSIBLE_UNIT);
        self::assertSame("$value $unit", (string)$measurement);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Measurements\Exceptions\UnknownUnit
     */
    public function I_cannot_create_measurement_with_unknown_unit()
    {
        new DeAbstractedMeasurement(123, 'non-existing unit');
    }

}

/** inner */
class DeAbstractedMeasurement extends AbstractMeasurement
{
    public const POSSIBLE_UNIT = 'foo';

    public function __construct($value, $unit)
    {
        parent::__construct($value, $unit);
    }

    /**
     * @return array|string[]
     */
    public function getPossibleUnits(): array
    {
        return [self::POSSIBLE_UNIT];
    }

}
