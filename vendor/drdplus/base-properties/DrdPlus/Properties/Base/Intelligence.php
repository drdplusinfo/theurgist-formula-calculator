<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Properties\Base;

use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Properties\Base\Partials\AbstractIntegerProperty;

/**
 * @method static Intelligence getIt(int | \Granam\Integer\IntegerInterface $value)
 * @method Intelligence add(int | \Granam\Integer\IntegerInterface $value)
 * @method Intelligence sub(int | \Granam\Integer\IntegerInterface $value)
 */
class Intelligence extends AbstractIntegerProperty implements BaseProperty
{
    /**
     * @return PropertyCode
     */
    public function getCode(): PropertyCode
    {
        return PropertyCode::getIt(PropertyCode::INTELLIGENCE);
    }

}