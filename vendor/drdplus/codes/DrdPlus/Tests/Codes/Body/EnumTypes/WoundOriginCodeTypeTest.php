<?php
namespace DrdPlus\Tests\Codes\Body\EnumTypes;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use DrdPlus\Codes\Body\EnumTypes\WoundOriginCodeType;
use DrdPlus\Codes\Body\OrdinaryWoundOriginCode;
use DrdPlus\Codes\Body\SeriousWoundOriginCode;
use DrdPlus\Tests\Codes\EnumTypes\AbstractCodeTypeWithSubTypesOnlyTest;

class WoundOriginCodeTypeTest extends AbstractCodeTypeWithSubTypesOnlyTest
{
    /**
     * @test
     * @throws \Doctrine\DBAL\DBALException
     */
    public function I_get_wound_origin_registered_as_subtype_enum(): void
    {
        WoundOriginCodeType::registerSelf();
        $woundOrigin = WoundOriginCodeType::getType(WoundOriginCodeType::WOUND_ORIGIN_CODE);
        /** @var AbstractPlatform $abstractPlatform */
        $abstractPlatform = $this->getPlatform();
        foreach (SeriousWoundOriginCode::getPossibleValues() as $ordinaryWoundsOriginValue) {
            $enumFromSubType = $woundOrigin->convertToPHPValue(
            // values of sub-types are persisted with class name as well
                SeriousWoundOriginCode::class . '::' . $ordinaryWoundsOriginValue,
                $abstractPlatform
            );
            self::assertInstanceOf(SeriousWoundOriginCode::class, $enumFromSubType);
            self::assertSame($ordinaryWoundsOriginValue, (string)$enumFromSubType);
        }
        foreach (OrdinaryWoundOriginCode::getPossibleValues() as $ordinaryWoundsOriginValue) {
            $enumFromSubType = $woundOrigin->convertToPHPValue(
            // values of sub-types are persisted with class name as well
                OrdinaryWoundOriginCode::class . '::' . $ordinaryWoundsOriginValue,
                $abstractPlatform
            );
            self::assertInstanceOf(OrdinaryWoundOriginCode::class, $enumFromSubType);
            self::assertSame($ordinaryWoundsOriginValue, (string)$enumFromSubType);
        }
    }
}