<?php declare(strict_types=1);

namespace DrdPlus\Tests\Codes\Wizard;

use DrdPlus\Tests\Codes\Partials\TranslatableCodeTest;

class SpellCodeTest extends TranslatableCodeTest
{
    protected function setUp(): void
    {
        self::assertStringContainsString(__NAMESPACE__, static::class, 'Code test has to be in "Tests" namespace');
    }
}