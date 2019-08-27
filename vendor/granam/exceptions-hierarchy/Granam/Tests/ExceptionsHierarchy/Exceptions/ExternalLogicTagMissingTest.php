<?php declare(strict_types=1);

namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

use Granam\ExceptionsHierarchy\Exceptions\InvalidTagInterfaceHierarchy;

class ExternalLogicTagMissingTest extends AbstractExceptionsHierarchyTest
{
    protected function getTestedNamespace()
    {
        return __NAMESPACE__ . '\DummyExceptionsHierarchy\ExternalLogicTagMissing';
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
    }

    /**
     * @test
     */
    public function My_exceptions_are_in_family_tree()
    {
        $this->expectException(InvalidTagInterfaceHierarchy::class);
        $this->expectExceptionMessageRegExp('~^Tag interface .+\\\Logic should extends external parent tag interface .+~');
        parent::My_exceptions_are_in_family_tree();
    }

}