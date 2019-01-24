<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\RulesSkeleton\Cache;

class CacheTest extends \DrdPlus\Tests\RulesSkeleton\CacheTest
{
    use Partials\AbstractContentTestTrait;

    protected static function getSutClass(string $sutTestClass = null, string $regexp = '~\\\Tests(.+)Test$~'): string
    {
        return Cache::class;
    }

}