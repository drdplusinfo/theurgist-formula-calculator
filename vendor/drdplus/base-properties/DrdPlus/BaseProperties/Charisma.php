<?php
declare(strict_types=1);

namespace DrdPlus\BaseProperties;

use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\BaseProperties\Partials\AbstractIntegerProperty;

/**
 * @method static Charisma getIt(int | \Granam\Integer\IntegerInterface $value)
 * @method Charisma add(int | \Granam\Integer\IntegerInterface $value)
 * @method Charisma sub(int | \Granam\Integer\IntegerInterface $value)
 */
class Charisma extends AbstractIntegerProperty implements BaseProperty
{
    public function getCode(): PropertyCode
    {
        return PropertyCode::getIt(PropertyCode::CHARISMA);
    }

}