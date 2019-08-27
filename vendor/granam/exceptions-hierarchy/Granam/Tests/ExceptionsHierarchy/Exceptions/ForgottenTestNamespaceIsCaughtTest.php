<?php declare(strict_types=1);

namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

class ForgottenTestNamespaceIsCaughtTest extends AbstractTestOfForgottenNamespaceIsCaught
{

    protected function getTestedNamespace()
    {
        /** @noinspection PhpExpressionResultUnusedInspection */
        'foo'; // intentionally "forgotten" return
    }

    protected function getRootNamespace()
    {
        return __NAMESPACE__;
    }

}
