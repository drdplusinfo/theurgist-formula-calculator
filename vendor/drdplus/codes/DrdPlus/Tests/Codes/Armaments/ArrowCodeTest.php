<?php declare(strict_types=1);

namespace DrdPlus\Tests\Codes\Armaments;

use DrdPlus\Codes\Armaments\ArrowCode;

class ArrowCodeTest extends ProjectileCodeTest
{
    /**
     * @test
     */
    public function I_can_find_out_if_is_arrow()
    {
        self::assertTrue(ArrowCode::getIt(ArrowCode::CRIPPLING_ARROW)->isArrow());
    }

    /**
     * @test
     */
    public function I_can_find_out_if_is_dart()
    {
        self::assertFalse(ArrowCode::getIt(ArrowCode::INCENDIARY_ARROW)->isDart());
    }

    /**
     * @test
     */
    public function I_can_find_out_if_is_sling_stone()
    {
        self::assertFalse(ArrowCode::getIt(ArrowCode::LONG_RANGE_ARROW)->isSlingStone());
    }

}