<?php declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\RulesSkeleton\RoutedDirs;

class RoutedDirsTest extends \DrdPlus\Tests\RulesSkeleton\RoutedDirsTest
{
    use Partials\CalculatorContentTestTrait;

    protected static function getSutClass(string $sutTestClass = null, string $regexp = '~\\\Tests(.+)Test$~'): string
    {
        return RoutedDirs::class;
    }

}