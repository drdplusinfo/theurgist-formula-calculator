<?php declare(strict_types=1);

namespace DrdPlus\BaseProperties;

use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\BaseProperties\Partials\AbstractIntegerProperty;

/**
 * @method static Strength getIt(int | \Granam\Integer\IntegerInterface $value)
 * @method Strength add(int | \Granam\Integer\IntegerInterface $value)
 * @method Strength sub(int | \Granam\Integer\IntegerInterface $value)
 */
class Strength extends AbstractIntegerProperty implements BaseProperty
{
    public function getCode(): PropertyCode
    {
        return PropertyCode::getIt(PropertyCode::STRENGTH);
    }

}