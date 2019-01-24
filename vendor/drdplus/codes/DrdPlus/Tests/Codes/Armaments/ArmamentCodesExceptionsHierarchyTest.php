<?php
namespace DrdPlus\Tests\Codes\Armaments;

use DrdPlus\Codes\Code;
use Granam\Tests\ExceptionsHierarchy\Exceptions\AbstractExceptionsHierarchyTest;

class ArmamentCodesExceptionsHierarchyTest extends AbstractExceptionsHierarchyTest
{
    /**
     * @return string
     */
    protected function getTestedNamespace()
    {
        return str_replace('\Tests', '', __NAMESPACE__);
    }

    /**
     * @return string
     */
    protected function getRootNamespace()
    {
        $reflection = new \ReflectionClass(Code::class);

        return $reflection->getNamespaceName();
    }

}