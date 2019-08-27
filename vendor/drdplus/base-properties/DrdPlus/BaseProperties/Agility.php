<?php declare(strict_types=1);

namespace DrdPlus\BaseProperties;

use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\BaseProperties\Partials\AbstractIntegerProperty;

/**
 * @method static Agility getIt(int | \Granam\Integer\IntegerInterface $value)
 * @method Agility add(int | \Granam\Integer\IntegerInterface $value)
 * @method Agility sub(int | \Granam\Integer\IntegerInterface $value)
 */
class Agility extends AbstractIntegerProperty implements BaseProperty
{
    public function getCode(): PropertyCode
    {
        return PropertyCode::getIt(PropertyCode::AGILITY);
    }

}