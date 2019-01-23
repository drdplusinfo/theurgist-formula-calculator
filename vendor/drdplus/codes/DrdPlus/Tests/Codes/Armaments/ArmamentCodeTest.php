<?php
namespace DrdPlus\Tests\Codes\Armaments;

use DrdPlus\Codes\Armaments\ArmamentCode;
use DrdPlus\Codes\Code;
use DrdPlus\Tests\Codes\Partials\TranslatableExtendableCodeTest;

abstract class ArmamentCodeTest extends TranslatableExtendableCodeTest
{
    /**
     * @test
     */
    public function Even_armament_code_itself_is_code()
    {
        self::assertTrue(is_a(ArmamentCode::class, Code::class, true));
    }

    /**
     * @test
     */
    public function It_is_armament_code()
    {
        self::assertTrue(is_a(self::getSutClass(), ArmamentCode::class, true));
    }
}