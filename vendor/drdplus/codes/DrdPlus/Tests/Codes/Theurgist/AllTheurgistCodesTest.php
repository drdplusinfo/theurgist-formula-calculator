<?php declare(strict_types=1);

namespace DrdPlus\Tests\Codes\Theurgist;

use DrdPlus\Codes\Theurgist\AbstractTheurgistCode;
use DrdPlus\Codes\Theurgist\DemonCode;
use DrdPlus\Tests\Codes\GetCodeClassesTrait;
use Granam\Tests\Tools\TestWithMockery;

class AllTheurgistCodesTest extends TestWithMockery
{
    use GetCodeClassesTrait;

    /**
     * @test
     */
    public function Every_theurgist_code_comes_from_abstract_theurgist_code()
    {
        foreach ($this->getCodeClasses(DemonCode::class) as $theurgistCodeClass) {
            self::assertTrue(
                is_a($theurgistCodeClass, AbstractTheurgistCode::class, true),
                "Expected $theurgistCodeClass to be instance of " . AbstractTheurgistCode::class
            );
            $expectedTestClass = str_replace('DrdPlus\\Codes', 'DrdPlus\\Tests\\Codes', $theurgistCodeClass) . 'Test';
            self::assertTrue(
                class_exists($expectedTestClass),
                "Expected $expectedTestClass to exists as a test of $theurgistCodeClass"
            );
            self::assertTrue(
                is_a($expectedTestClass, AbstractTheurgistCodeTest::class, true),
                "Expected test class $expectedTestClass to be instance of " . AbstractTheurgistCodeTest::class
            );
        }
    }
}