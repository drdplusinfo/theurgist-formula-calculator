<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\Measurements;

interface MeasurementWithBonus extends Measurement
{
    /**
     * @return Bonus
     */
    public function getBonus();

}