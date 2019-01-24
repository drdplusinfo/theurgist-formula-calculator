<?php
namespace DrdPlus\Tests\Codes\Transport;

use DrdPlus\Codes\Transport\RidingAnimalMovementCode;
use DrdPlus\Tests\Codes\Partials\TranslatableCodeTest;

class RidingAnimalMovementCodeTest extends TranslatableCodeTest
{
    /**
     * @test
     */
    public function I_can_get_movements_without_jump()
    {
        $allValues = RidingAnimalMovementCode::getPossibleValues();
        self::assertContains(RidingAnimalMovementCode::JUMPING, $allValues);
        $withoutJumping = array_filter($allValues, function (string $movement) {
            return $movement !== RidingAnimalMovementCode::JUMPING;
        });
        self::assertSame($withoutJumping, RidingAnimalMovementCode::getPossibleValuesWithoutJumping());
    }
}