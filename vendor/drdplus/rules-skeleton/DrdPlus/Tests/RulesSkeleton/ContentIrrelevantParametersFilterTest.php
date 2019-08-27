<?php declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\ContentIrrelevantParametersFilter;
use PHPUnit\Framework\TestCase;

class ContentIrrelevantParametersFilterTest extends TestCase
{

    /**
     * @test
     */
    public function I_can_remove_content_irrelevant_parameters(): void
    {
        $contentIrrelevantParametersFilter = new ContentIrrelevantParametersFilter(['foo', 'bar']);
        self::assertSame(
            ['baz' => 123, 'qux' => false],
            $contentIrrelevantParametersFilter->removeContentIrrelevantParameters(['baz' => 123, 'qux' => false])
        );
        self::assertSame(
            ['baz' => 123, 'qux' => false],
            $contentIrrelevantParametersFilter->removeContentIrrelevantParameters([
                'foo' => 123,
                'baz' => 123,
                'qux' => false,
                'bar' => 'sdgseth',
            ])
        );
    }
}
