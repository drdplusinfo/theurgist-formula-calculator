<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton\Web;

use DrdPlus\RulesSkeleton\Web\Head;
use DrdPlus\Tests\CalculatorSkeleton\Partials\AbstractContentTestTrait;

class HeadTest extends \DrdPlus\Tests\RulesSkeleton\Web\HeadTest
{
    use AbstractContentTestTrait;

    protected static function getSutClass(string $sutTestClass = null, string $regexp = '~\\\Tests(.+)Test$~'): string
    {
        return Head::class;
    }
}