<?php
declare(strict_types=1);

namespace DrdPlus\BaseProperties;

use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\BaseProperties\Partials\AbstractIntegerProperty;

/**
 * @method static Knack getIt(int | \Granam\Integer\IntegerInterface $value)
 * @method Knack add(int | \Granam\Integer\IntegerInterface $value)
 * @method Knack sub(int | \Granam\Integer\IntegerInterface $value)
 */
class Knack extends AbstractIntegerProperty implements BaseProperty
{
    public function getCode(): PropertyCode
    {
        return PropertyCode::getIt(PropertyCode::KNACK);
    }

}