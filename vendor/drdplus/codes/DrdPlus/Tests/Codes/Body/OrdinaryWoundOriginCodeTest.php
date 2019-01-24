<?php
namespace DrdPlus\Tests\Codes\Body;

use DrdPlus\Codes\Body\OrdinaryWoundOriginCode;

class OrdinaryWoundOriginCodeTest extends WoundOriginCodeTest
{
    /**
     * @test
     */
    public function I_can_use_it(): void
    {
        $ordinaryWoundOrigin = OrdinaryWoundOriginCode::getIt();
        $sameOrdinaryWoundOrigin = OrdinaryWoundOriginCode::getIt(OrdinaryWoundOriginCode::ORDINARY);
        self::assertSame($ordinaryWoundOrigin, $sameOrdinaryWoundOrigin);
        self::assertSame($ordinaryWoundOrigin, OrdinaryWoundOriginCode::getEnum('ordinary'));
        self::assertTrue($ordinaryWoundOrigin->isOrdinaryWoundOrigin());
        self::assertFalse($ordinaryWoundOrigin->isSeriousWoundOrigin());
    }

    /**
     * @test
     * @expectedException \DrdPlus\Codes\Partials\Exceptions\UnknownValueForCode
     * @expectedExceptionMessageRegExp ~Kitchen accident~
     */
    public function I_can_not_create_custom_ordinary_origin(): void
    {
        OrdinaryWoundOriginCode::getEnum('Kitchen accident');
    }
}
