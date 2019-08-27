<?php declare(strict_types=1);

namespace DrdPlus\Tests\Codes\Body;

use DrdPlus\Codes\Body\OrdinaryWoundOriginCode;

class OrdinaryWoundOriginCodeTest extends WoundOriginCodeTest
{
    /**
     * @test
     */
    public function I_can_use_it()
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
     */
    public function I_can_not_create_custom_ordinary_origin()
    {
        $this->expectException(\DrdPlus\Codes\Partials\Exceptions\UnknownValueForCode::class);
        $this->expectExceptionMessageRegExp('~Kitchen accident~');
        OrdinaryWoundOriginCode::getEnum('Kitchen accident');
    }
}
