<?php declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\RulesSkeleton\CookiesService;

class CookiesServiceTest extends \DrdPlus\Tests\RulesSkeleton\CookiesServiceTest
{
    use Partials\CalculatorContentTestTrait;

    protected static function getSutClass(string $sutTestClass = null, string $regexp = '~\\\Tests(.+)Test$~'): string
    {
        return CookiesService::class;
    }
}