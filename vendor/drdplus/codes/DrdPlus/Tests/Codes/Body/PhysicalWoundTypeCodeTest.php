<?php
namespace DrdPlus\Tests\Codes\Body;

use DrdPlus\Codes\Body\PhysicalWoundTypeCode;
use DrdPlus\Tests\Codes\Partials\TranslatableCodeTest;

class PhysicalWoundTypeCodeTest extends TranslatableCodeTest
{
    /**
     * @test
     * @dataProvider provideType
     * @param string $value
     * @param bool $isCrush
     * @param bool $isStab
     * @param bool $isCut
     */
    public function I_can_ask_it_for_type(string $value, bool $isCrush, bool $isStab, bool $isCut): void
    {
        $woundTypeCode = PhysicalWoundTypeCode::getIt($value);
        self::assertSame($isCrush, $woundTypeCode->isCrush());
        self::assertSame($isStab, $woundTypeCode->isStab());
        self::assertSame($isCut, $woundTypeCode->isCut());
    }

    public function provideType(): array
    {
        return [
            [PhysicalWoundTypeCode::CRUSH, true, false, false],
            [PhysicalWoundTypeCode::STAB, false, true, false],
            [PhysicalWoundTypeCode::CUT, false, false, true],
        ];
    }
}