<?php declare(strict_types=1);

namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

use Granam\Tests\ExceptionsHierarchy\Exceptions\DummyExceptionsHierarchy\CanNotBeLoadedByAutoLoader\WithWronglyNamedClass\AutoLoaderCanNotFindMeBecauseSomeoneNamedMeWrongly;

class NotAutoLoadedExceptionClassIsReportedTest extends AbstractExceptionsHierarchyTest
{
    /** @noinspection SenselessProxyMethodInspection */
    /**
     * @test
* @expectExceptionMessageRegExp ~class .+ interface .+AutoLoaderCanNotFindMeBecauseSomeoneNamedMeWrongly~
     */
    public function My_exceptions_are_in_family_tree()
    {
        $this->expectException(\Granam\ExceptionsHierarchy\Exceptions\ExceptionClassNotFoundByAutoloader::class);
        parent::My_exceptions_are_in_family_tree();
    }

    protected function getTestedNamespace()
    {
        return __NAMESPACE__ . '\DummyExceptionsHierarchy\CanNotBeLoadedByAutoLoader\WithWronglyNamedClass';
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
            AutoLoaderCanNotFindMeBecauseSomeoneNamedMeWrongly::class,
        ];
    }

}