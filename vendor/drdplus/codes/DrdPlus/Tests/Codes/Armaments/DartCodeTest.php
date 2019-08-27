<?php declare(strict_types=1);

namespace DrdPlus\Tests\Codes\Armaments;

use DrdPlus\Codes\Armaments\DartCode;

class DartCodeTest extends ProjectileCodeTest
{
    /**
     * @test
     */
    public function I_can_find_out_if_is_arrow()
    {
        self::assertFalse(DartCode::getIt(DartCode::HOLLOW_DART)->isArrow());
    }

    /**
     * @test
     */
    public function I_can_find_out_if_is_dart()
    {
        self::assertTrue(DartCode::getIt(DartCode::SILVER_DART)->isDart());
    }

    /**
     * @test
     */
    public function I_can_find_out_if_is_sling_stone()
    {
        self::assertFalse(DartCode::getIt(DartCode::WAR_DART)->isSlingStone());
    }

}