<?php
declare(strict_types=1);

namespace Granam\DiceRolls\Templates\RollOn;

class RollOn2 extends AbstractRollOn
{

    /**
     * @param int $rolledValue
     *
     * @return bool
     */
    public function shouldHappen(int $rolledValue): bool
    {
        return $rolledValue === 2;
    }

}