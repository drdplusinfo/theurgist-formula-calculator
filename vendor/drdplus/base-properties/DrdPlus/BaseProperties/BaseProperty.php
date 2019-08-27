<?php declare(strict_types=1);

namespace DrdPlus\BaseProperties;

use DrdPlus\Codes\Properties\PropertyCode;
use Granam\Integer\IntegerInterface;

/**
 * @method static BaseProperty getIt(int | IntegerInterface $value)
 * @method PropertyCode getCode()
 */
interface BaseProperty extends Property, IntegerInterface
{

}