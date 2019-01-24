<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\RulesSkeleton\Dirs;

class DirsTest extends \DrdPlus\Tests\RulesSkeleton\DirsTest
{
    use Partials\AbstractContentTestTrait;

    protected static function getSutClass(string $sutTestClass = null, string $regexp = '~\\\Tests(.+)Test$~'): string
    {
        return Dirs::class;
    }
}