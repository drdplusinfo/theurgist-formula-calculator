<?php
declare(strict_types=1);

namespace Granam\Tests\DiceRolls\Templates\Dices;

use Granam\DiceRolls\Templates\Dices\CustomDice;

class DicesExceptionsHierarchyTest extends \Granam\Tests\DiceRolls\DiceRollsExceptionsHierarchyTest
{
    protected function getTestedNamespace(): string
    {
        $reflection = new \ReflectionClass(CustomDice::class);

        return $reflection->getNamespaceName();
    }

}