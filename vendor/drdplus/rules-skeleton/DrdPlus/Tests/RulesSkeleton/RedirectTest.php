<?php declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\Redirect;
use PHPUnit\Framework\TestCase;

class RedirectTest extends TestCase
{
    /**
     * @test
     */
    public function I_can_use_it(): void
    {
        $redirect = new Redirect('foo', 123);
        self::assertSame('foo', $redirect->getTarget());
        self::assertSame(123, $redirect->getAfterSeconds());
    }
}