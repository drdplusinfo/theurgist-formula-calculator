<?php declare(strict_types=1);

namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

use Granam\ExceptionsHierarchy\Exceptions\MissingNamespace;
use Granam\ExceptionsHierarchy\TestOfExceptionsHierarchy;
use PHPUnit\Framework\TestCase;

abstract class AbstractTestOfForgottenNamespaceIsCaught extends TestCase
{

    /**
     * @return string
     */
    abstract protected function getTestedNamespace();

    /**
     * @return string
     */
    abstract protected function getRootNamespace();

    /**
     * @test
     */
    public function I_am_stopped_if_forgot_return_of_tested_namespace()
    {
        $this->expectException(MissingNamespace::class);
        $this->expectExceptionMessageRegExp('~given NULL$~');
        new TestOfExceptionsHierarchy($this->getTestedNamespace(), $this->getRootNamespace());
    }

}