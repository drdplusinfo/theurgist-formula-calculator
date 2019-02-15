<?php
declare(strict_types=1);

namespace DrdPlus\Tests\BaseProperties;

use DrdPlus\BaseProperties\Agility;
use DrdPlus\BaseProperties\BasePropertiesFactory;
use DrdPlus\BaseProperties\Charisma;
use DrdPlus\BaseProperties\Intelligence;
use DrdPlus\BaseProperties\Knack;
use DrdPlus\BaseProperties\Strength;
use DrdPlus\BaseProperties\Will;
use DrdPlus\Codes\Properties\PropertyCode;
use PHPUnit\Framework\TestCase;

class BasePropertiesFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function I_can_create_every_property(): void
    {
        $factory = new BasePropertiesFactory();

        $strength = $factory->createStrength($strengthValue = 123);
        self::assertInstanceOf(Strength::class, $strength);
        self::assertSame($strengthValue, $strength->getValue());
        $strength = $factory->createProperty($strengthValue, PropertyCode::STRENGTH);
        self::assertInstanceOf(Strength::class, $strength);
        self::assertSame($strengthValue, $strength->getValue());

        $agility = $factory->createAgility($agilityValue = 123);
        self::assertInstanceOf(Agility::class, $agility);
        self::assertSame($agilityValue, $agility->getValue());
        $agility = $factory->createProperty($agilityValue, PropertyCode::AGILITY);
        self::assertInstanceOf(Agility::class, $agility);
        self::assertSame($agilityValue, $agility->getValue());

        $knack = $factory->createKnack($knackValue = 123);
        self::assertInstanceOf(Knack::class, $knack);
        self::assertSame($knackValue, $knack->getValue());
        $knack = $factory->createProperty($knackValue, PropertyCode::KNACK);
        self::assertInstanceOf(Knack::class, $knack);
        self::assertSame($knackValue, $knack->getValue());

        $will = $factory->createWill($willValue = 123);
        self::assertInstanceOf(Will::class, $will);
        self::assertSame($willValue, $will->getValue());
        $will = $factory->createProperty($willValue, PropertyCode::WILL);
        self::assertInstanceOf(Will::class, $will);
        self::assertSame($willValue, $will->getValue());

        $intelligence = $factory->createIntelligence($intelligenceValue = 123);
        self::assertInstanceOf(Intelligence::class, $intelligence);
        self::assertSame($intelligenceValue, $intelligence->getValue());
        $intelligence = $factory->createProperty($intelligenceValue, PropertyCode::INTELLIGENCE);
        self::assertInstanceOf(Intelligence::class, $intelligence);
        self::assertSame($intelligenceValue, $intelligence->getValue());

        $charisma = $factory->createCharisma($charismaValue = 123);
        self::assertInstanceOf(Charisma::class, $charisma);
        self::assertSame($charismaValue, $charisma->getValue());
        $charisma = $factory->createProperty($charismaValue, PropertyCode::CHARISMA);
        self::assertInstanceOf(Charisma::class, $charisma);
        self::assertSame($charismaValue, $charisma->getValue());
    }

    /**
     * @test
     * @expectedException \DrdPlus\BaseProperties\Exceptions\UnknownBasePropertyCode
     */
    public function I_can_not_create_property_by_unknown_code(): void
    {
        $factory = new BasePropertiesFactory();

        $factory->createProperty(123, 'unknown code');
    }
}