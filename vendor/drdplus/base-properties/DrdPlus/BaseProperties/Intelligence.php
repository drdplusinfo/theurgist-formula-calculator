<?php declare(strict_types=1);

namespace DrdPlus\BaseProperties;

use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\BaseProperties\Partials\AbstractIntegerProperty;

/**
 * @method static Intelligence getIt(int | \Granam\Integer\IntegerInterface $value)
 * @method Intelligence add(int | \Granam\Integer\IntegerInterface $value)
 * @method Intelligence sub(int | \Granam\Integer\IntegerInterface $value)
 */
class Intelligence extends AbstractIntegerProperty implements BaseProperty
{
    public function getCode(): PropertyCode
    {
        return PropertyCode::getIt(PropertyCode::INTELLIGENCE);
    }

}