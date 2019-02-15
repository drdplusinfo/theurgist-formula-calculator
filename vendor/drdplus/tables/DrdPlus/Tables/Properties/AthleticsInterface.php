<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Properties;

use Granam\Integer\PositiveInteger;

/**
 * Just an interface to cover requirements. It is not implemented in this library.
 */
interface AthleticsInterface
{
    /**
     * @return PositiveInteger
     */
    public function getAthleticsBonus(): PositiveInteger;
}