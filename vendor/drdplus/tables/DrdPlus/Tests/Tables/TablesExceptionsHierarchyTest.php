<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables;

use DrdPlus\Tables\Table;
use Granam\Tests\ExceptionsHierarchy\Exceptions\AbstractExceptionsHierarchyTest;

class TablesExceptionsHierarchyTest extends AbstractExceptionsHierarchyTest
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
        $reflection = new \ReflectionClass(Table::class);

        return $reflection->getNamespaceName();
    }

}