<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Measurements;

interface MeasurementWithBonus extends Measurement
{
    /**
     * @return Bonus
     */
    public function getBonus();

}