<?php
declare(strict_types = 1);

namespace DrdPlus\Tests\Codes\Theurgist;

use DrdPlus\Codes\Theurgist\ModifierCode;

class ModifierCodeTest extends AbstractTheurgistCodeTest
{
    protected function getValuesSameInCzechAndEnglish(): array
    {
        return [ModifierCode::RECEPTOR];
    }

}