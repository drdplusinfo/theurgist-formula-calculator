<?php declare(strict_types=1);

namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

use Granam\ExceptionsHierarchy\TestOfExceptionsHierarchy;
use PHPUnit\Framework\TestCase;

abstract class AbstractExceptionsHierarchyTest extends TestCase
{

    /**
     * @var TestOfExceptionsHierarchy
     */
    private $testOfExceptionsHierarchy;

    protected function setUp(): void
    {
        $this->testOfExceptionsHierarchy = new TestOfExceptionsHierarchy(
            $this->getTestedNamespace(),
            $this->getRootNamespace(),
            $this->getExceptionsSubDir(),
            (array)$this->getExternalRootNamespaces(),
            $this->getExternalRootExceptionsSubDir()
        );
    }

    /**
     * @return TestOfExceptionsHierarchy
     */
    protected function getTestOfExceptionsHierarchy()
    {
        return $this->testOfExceptionsHierarchy;
    }

    /**
     * @return string
     */
    abstract protected function getTestedNamespace();

    /**
     * @return string
     */
    abstract protected function getRootNamespace();

    /**
     * @return string
     */
    protected function getExceptionsSubDir()
    {
        return 'Exceptions';
    }

    /**
     * For a single external root namespace can return just a string.
     *
     * @return array|string[]|string
     */
    protected function getExternalRootNamespaces()
    {
        return [];
    }

    protected function getExternalRootExceptionsSubDir()
    {
        return 'Exceptions';
    }

    /**
     * @test
     */
    public function My_exceptions_are_in_family_tree()
    {
        self::assertTrue($this->getTestOfExceptionsHierarchy()->My_exceptions_are_in_family_tree());
    }

    /**
     * @test
     * @depends My_exceptions_are_in_family_tree
     */
    public function My_exceptions_are_used()
    {
        self::assertTrue(
            $this->getTestOfExceptionsHierarchy()->My_exceptions_are_used(
                $this->getExceptionsUsageRootDir(),
                $this->getExceptionClassesSkippedFromUsageTest()
            )
        );
    }

    /**
     * @return string
     */
    protected function getExceptionsUsageRootDir()
    {
        return ''; // empty for same dir as exceptions are or upper level moving against exceptions sub dir
    }

    /**
     * @return array|string[]
     */
    protected function getExceptionClassesSkippedFromUsageTest()
    {
        return [];
    }

}