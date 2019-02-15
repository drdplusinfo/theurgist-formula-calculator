<?php
declare(strict_types=1);

namespace DrdPlus\BaseProperties;

use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\BaseProperties\Partials\AbstractIntegerProperty;

/**
 * @method static Will getIt(int | \Granam\Integer\IntegerInterface $value)
 * @method Will add(int | \Granam\Integer\IntegerInterface $value)
 * @method Will sub(int | \Granam\Integer\IntegerInterface $value)
 */
class Will extends AbstractIntegerProperty implements BaseProperty
{
    public function getCode(): PropertyCode
    {
        return PropertyCode::getIt(PropertyCode::WILL);
    }

}