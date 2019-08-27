<?php declare(strict_types=1);

namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

class ExternalRuntimeTagMissingTest extends AbstractExceptionsHierarchyTest
{
    protected function getTestedNamespace()
    {
        return __NAMESPACE__ . '\DummyExceptionsHierarchy\ExternalRuntimeTagMissing';
    }

    protected function getRootNamespace()
    {
        return $this->getTestedNamespace();
    }

    protected function getExceptionsSubDir()
    {
        return '';
    }

    protected function getExternalRootNamespaces()
    {
        // skipping some namespace chain up from root namespace
        return ['\Granam\ExceptionsHierarchy'];
    }/** @noinspection SenselessProxyMethodInspection */

    /**
     * @test
* @expectExceptionMessageRegExp ~^Tag interface .+\\Runtime should extends external parent tag interface .+~
     */
    public function My_exceptions_are_in_family_tree()
    {
        $this->expectException(\Granam\ExceptionsHierarchy\Exceptions\InvalidTagInterfaceHierarchy::class);
        parent::My_exceptions_are_in_family_tree();
    }

}