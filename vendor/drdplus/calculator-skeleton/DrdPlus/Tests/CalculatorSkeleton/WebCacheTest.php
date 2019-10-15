<?php declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\RulesSkeleton\WebCache;

class WebCacheTest extends \DrdPlus\Tests\RulesSkeleton\WebCacheTest
{
    use Partials\CalculatorContentTestTrait;

    protected static function getSutClass(string $sutTestClass = null, string $regexp = '~\\\Tests(.+)Test$~'): string
    {
        return WebCache::class;
    }

}