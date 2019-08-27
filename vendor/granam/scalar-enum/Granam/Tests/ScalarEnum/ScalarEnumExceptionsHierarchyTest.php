<?php declare(strict_types=1);

namespace Granam\Tests\ScalarEnum;

use Granam\Tests\ExceptionsHierarchy\Exceptions\AbstractExceptionsHierarchyTest;

class ScalarEnumExceptionsHierarchyTest extends AbstractExceptionsHierarchyTest
{
    /**
     * @return string
     */
    protected function getTestedNamespace(): string
    {
        return $this->getRootNamespace();
    }

    /**
     * @return string
     */
    protected function getRootNamespace(): string
    {
        return \str_replace('\Tests', '', __NAMESPACE__);
    }

    /**
     * @return string
     */
    protected function getExternalRootNamespaces(): string
    {
        return 'Granam\Scalar';
    }

}