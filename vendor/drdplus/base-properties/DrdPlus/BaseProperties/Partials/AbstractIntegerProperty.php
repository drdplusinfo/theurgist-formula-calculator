<?php declare(strict_types=1);

namespace DrdPlus\BaseProperties\Partials;

use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\BaseProperties\Property;
use Granam\Integer\IntegerInterface;
use Granam\Integer\Tools\ToInteger;
use Granam\IntegerEnum\IntegerEnum;

/**
 * @method PropertyCode getCode()
 * @method static AbstractIntegerProperty getEnum(int|IntegerInterface $value)
 */
abstract class AbstractIntegerProperty extends IntegerEnum implements Property
{
    /**
     * @param int|IntegerInterface $value
     * @return AbstractIntegerProperty
     */
    public static function getIt($value)
    {
        return static::getEnum($value);
    }

    /**
     * @param int|IntegerInterface $value
     * @return AbstractIntegerProperty
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     */
    public function add($value)
    {
        $increased = static::getIt($this->getValue() + ToInteger::toInteger($value));

        return $increased;
    }

    /**
     * @param int|IntegerInterface $value
     * @return AbstractIntegerProperty
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     */
    public function sub($value)
    {
        $decreased = static::getIt($this->getValue() - ToInteger::toInteger($value));

        return $decreased;
    }
}