<?php declare(strict_types=1);

namespace DrdPlus\Tests\Codes\Units;

use DrdPlus\Codes\Partials\TranslatableCode;
use DrdPlus\Tests\Codes\Partials\TranslatableCodeTest;

class DistanceUnitCodeTest extends TranslatableCodeTest
{
    protected function getExpectedCzechTranslationOfFewDecimal(TranslatableCode $translatableCode): string
    {
        self::assertTrue(\in_array($translatableCode->getValue(), ['decimeter', 'meter'], true));

        return $translatableCode->getValue() === 'decimeter'
            ? 'decimetru'
            : 'metru';
    }
}