<?php declare(strict_types=1);

namespace DrdPlus\Tests\Codes\Theurgist;

use DrdPlus\Codes\Theurgist\DemonCode;

class DemonCodeTest extends AbstractTheurgistCodeTest
{
    protected function getValuesSameInCzechAndEnglish(): array
    {
        return [DemonCode::GOLEM, DemonCode::BERSERK];
    }

}