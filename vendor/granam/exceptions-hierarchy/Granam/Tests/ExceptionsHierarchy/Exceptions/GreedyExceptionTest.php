<?php declare(strict_types=1);

namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

use Granam\ExceptionsHierarchy\Exceptions\ExceptionIsNotTaggedProperly;
use Granam\Tests\ExceptionsHierarchy\Exceptions\DummyExceptionsHierarchy\GreedyException\BothRuntimeAndLogicTagged;

class GreedyExceptionTest extends AbstractExceptionsHierarchyTest
{
    /** @noinspection SenselessProxyMethodInspection */

    /**
     * @test
     */
    public function My_exceptions_are_in_family_tree()
    {
        $this->expectException(ExceptionIsNotTaggedProperly::class);
        $this->expectExceptionMessageRegExp('~ can not be tagged by Runtime interface and Logic interface at the same time$~');
        parent::My_exceptions_are_in_family_tree();
    }

    protected function getTestedNamespace()
    {
        return __NAMESPACE__ . '\DummyExceptionsHierarchy\GreedyException';
    }

    protected function getExceptionsSubDir()
    {
        return false;
    }

    protected function getRootNamespace()
    {
        return $this->getTestedNamespace();
    }

    protected function getExceptionClassesSkippedFromUsageTest()
    {
        return [
            BothRuntimeAndLogicTagged::class,
        ];
    }

}