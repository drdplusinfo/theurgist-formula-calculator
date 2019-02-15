<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Measurements\Tools;

interface EvaluatorInterface
{

    /**
     * @param int $maxRollToGetValue
     * @return int
     */
    public function evaluate(int $maxRollToGetValue): int;
}