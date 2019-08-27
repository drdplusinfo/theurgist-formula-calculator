<?php declare(strict_types=1);

namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

class RuntimeTagWithoutBaseTagTest extends AbstractExceptionsHierarchyTest
{
    /** @noinspection SenselessProxyMethodInspection */

    /**
     * @test
* @expectExceptionMessageRegExp ~^Runtime tag interface .+ is not a child of .+\\Exception$$~
     */
    public function My_exceptions_are_in_family_tree()
    {
        $this->expectException(\Granam\ExceptionsHierarchy\Exceptions\InvalidTagInterfaceHierarchy::class);
        parent::My_exceptions_are_in_family_tree();
    }

    protected function getTestedNamespace()
    {
        return __NAMESPACE__ . '\DummyExceptionsHierarchy\RuntimeTagWithoutBaseTag';
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