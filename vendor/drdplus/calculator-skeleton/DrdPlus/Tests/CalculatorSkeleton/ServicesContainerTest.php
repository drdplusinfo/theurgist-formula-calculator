<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\RulesSkeleton\ServicesContainer;

class ServicesContainerTest extends \DrdPlus\Tests\RulesSkeleton\ServicesContainerTest
{
    use Partials\AbstractContentTestTrait;

    protected static function getSutClass(string $sutTestClass = null, string $regexp = '~\\\Tests(.+)Test$~'): string
    {
        return ServicesContainer::class;
    }
}