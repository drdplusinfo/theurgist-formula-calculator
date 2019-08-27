<?php declare(strict_types=1);

namespace Granam\Tests\DiceRolls;

use Granam\DiceRolls\DiceRoll;
use Granam\Tests\ExceptionsHierarchy\Exceptions\AbstractExceptionsHierarchyTest;

class DiceRollsExceptionsHierarchyTest extends AbstractExceptionsHierarchyTest
{
    /**
     * @return string
     */
    protected function getRootNamespace(): string
    {
        $reflection = new \ReflectionClass(DiceRoll::class);

        return $reflection->getNamespaceName();
    }

    /**
     * @return string
     */
    protected function getTestedNamespace(): string
    {
        return $this->getRootNamespace();
    }
}