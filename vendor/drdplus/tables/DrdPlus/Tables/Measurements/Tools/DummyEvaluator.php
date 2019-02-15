<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Measurements\Tools;

use DrdPlus\Tables\Measurements\Exceptions\UnsupportedMethodCalled;

class DummyEvaluator implements EvaluatorInterface
{
    /**
     * @param int $maxRollToGetValue
     * @return int
     * @throws \DrdPlus\Tables\Measurements\Exceptions\UnsupportedMethodCalled
     */
    public function evaluate(int $maxRollToGetValue): int
    {
        throw new UnsupportedMethodCalled('Dummy evaluator should never be called');
    }

}