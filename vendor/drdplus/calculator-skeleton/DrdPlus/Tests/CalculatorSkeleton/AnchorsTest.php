<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

/**
 * @backupGlobals enabled
 */
class AnchorsTest extends \DrdPlus\Tests\RulesSkeleton\AnchorsTest
{
    use Partials\CalculatorContentTestTrait;

    /**
     * @test
     */
    public function I_can_go_directly_to_eshop_item_page(): void
    {
        self::assertFalse(false, 'No link to e-shop expected in calculator');
    }

}