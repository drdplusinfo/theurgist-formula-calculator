<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton\Web;

use DrdPlus\RulesSkeleton\Web\Head;
use DrdPlus\Tests\CalculatorSkeleton\Partials\CalculatorContentTestTrait;

class HeadTest extends \DrdPlus\Tests\RulesSkeleton\Web\HeadTest
{
    use CalculatorContentTestTrait;

    protected static function getSutClass(string $sutTestClass = null, string $regexp = '~\\\Tests(.+)Test$~'): string
    {
        return Head::class;
    }
}