<?php
namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

class ExternalExceptionTagMissingTest extends AbstractExceptionsHierarchyTest
{
    protected function getTestedNamespace()
    {
        return __NAMESPACE__ . '\DummyExceptionsHierarchy\ExternalExceptionTagMissing';
    }

    protected function getRootNamespace()
    {
        return $this->getTestedNamespace();
    }

    protected function getExceptionsSubDir()
    {
        return false;
    }

    protected function getExternalRootNamespaces()
    {
        // skipping some namespace chain up from root namespace
        return ['\Granam\ExceptionsHierarchy'];
    }/** @noinspection SenselessProxyMethodInspection */

    /**
     * @test
     * @expectedException \Granam\ExceptionsHierarchy\Exceptions\InvalidTagInterfaceHierarchy
     * @expectedExceptionMessageRegExp ~^Tag interface .+\\Exception should extends external parent tag interface .+~
     */
    public function My_exceptions_are_in_family_tree()
    {
        // overloading parent method annotations
        parent::My_exceptions_are_in_family_tree();
    }

}