<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Properties\Base;

use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Properties\Property;
use Granam\Integer\IntegerInterface;

/**
 * @method static BaseProperty getIt(int | IntegerInterface $value)
 * @method PropertyCode getCode()
 */
interface BaseProperty extends Property, IntegerInterface
{

}