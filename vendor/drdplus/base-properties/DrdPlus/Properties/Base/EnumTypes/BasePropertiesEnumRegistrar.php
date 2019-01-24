<?php
declare(strict_types=1);
/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Properties\Base\EnumTypes;

use Granam\Strict\Object\StrictObject;

class BasePropertiesEnumRegistrar extends StrictObject
{
    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    public static function registerAll(): void
    {
        StrengthType::registerSelf();
        AgilityType::registerSelf();
        KnackType::registerSelf();
        WillType::registerSelf();
        IntelligenceType::registerSelf();
        CharismaType::registerSelf();
    }
}