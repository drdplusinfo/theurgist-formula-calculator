<?php declare(strict_types=1);

namespace DrdPlus\BaseProperties;

use DrdPlus\Codes\Properties\PropertyCode;
use Granam\Integer\IntegerInterface;
use Granam\Scalar\Tools\ToString;
use Granam\String\StringInterface;
use Granam\Tools\ValueDescriber;
use Granam\Strict\Object\StrictObject;

class BasePropertiesFactory extends StrictObject
{
    /**
     * @param int|IntegerInterface $strengthValue
     * @return Strength
     */
    public function createStrength($strengthValue): Strength
    {
        return Strength::getIt($strengthValue);
    }

    /**
     * @param int|IntegerInterface $agilityValue
     * @return Agility
     */
    public function createAgility($agilityValue): Agility
    {
        return Agility::getIt($agilityValue);
    }

    /**
     * @param int|IntegerInterface $knackValue
     * @return Knack
     */
    public function createKnack($knackValue): Knack
    {
        return Knack::getIt($knackValue);
    }

    /**
     * @param int|IntegerInterface $willValue
     * @return Will
     */
    public function createWill($willValue): Will
    {
        return Will::getIt($willValue);
    }

    /**
     * @param int|IntegerInterface $intelligenceValue
     * @return Intelligence
     */
    public function createIntelligence($intelligenceValue): Intelligence
    {
        return Intelligence::getIt($intelligenceValue);
    }

    /**
     * @param int|IntegerInterface $charismaValue
     * @return Charisma
     */
    public function createCharisma($charismaValue): Charisma
    {
        return Charisma::getIt($charismaValue);
    }

    /**
     * @param $propertyValue
     * @param string|StringInterface $propertyCode
     * @return Agility|Charisma|Intelligence|Knack|Strength|Will|BaseProperty
     * @throws \DrdPlus\BaseProperties\Exceptions\UnknownBasePropertyCode
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    public function createProperty($propertyValue, $propertyCode): BaseProperty
    {
        switch (ToString::toString($propertyCode)) {
            case PropertyCode::STRENGTH :
                return $this->createStrength($propertyValue);
            case PropertyCode::AGILITY :
                return $this->createAgility($propertyValue);
            case PropertyCode::KNACK :
                return $this->createKnack($propertyValue);
            case PropertyCode::WILL :
                return $this->createWill($propertyValue);
            case PropertyCode::INTELLIGENCE :
                return $this->createIntelligence($propertyValue);
            case PropertyCode::CHARISMA :
                return $this->createCharisma($propertyValue);
            default :
                throw new Exceptions\UnknownBasePropertyCode(
                    'Unknown code of a base property ' . ValueDescriber::describe($propertyCode)
                );
        }
    }
}