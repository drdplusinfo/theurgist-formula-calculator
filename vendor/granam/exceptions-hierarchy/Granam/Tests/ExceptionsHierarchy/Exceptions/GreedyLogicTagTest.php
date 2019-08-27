<?php declare(strict_types=1);

namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

use Granam\ExceptionsHierarchy\Exceptions\InvalidTagInterfaceHierarchy;

class GreedyLogicTagTest extends AbstractExceptionsHierarchyTest
{
    /**
     * @test
     */
    public function My_exceptions_are_in_family_tree()
    {
        $this->expectException(InvalidTagInterfaceHierarchy::class);
        $this->expectExceptionMessageRegExp('~Logic tag interface .+\\\Logic can not be a runtime tag~');
        parent::My_exceptions_are_in_family_tree();
    }

    protected function getTestedNamespace()
    {
        return __NAMESPACE__ . '\DummyExceptionsHierarchy\GreedyLogicTag';
    }

    protected function getExceptionsSubDir()
    {
        return false;
    }

    protected function getRootNamespace()
    {
        return $this->getTestedNamespace();
    }
}