<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\Measurements\Tools;

use Granam\DiceRolls\Templates\Rollers\Roller1d6;

class DiceChanceEvaluator implements EvaluatorInterface
{
    /**
     * @var Roller1d6
     */
    private $roller1d6;

    public function __construct(Roller1d6 $roller1d6)
    {
        $this->roller1d6 = $roller1d6;
    }

    public function evaluate(int $maxRollToGetValue): int
    {
        if ($this->roller1d6->roll()->getValue() <= $maxRollToGetValue) {
            return 1;
        }

        return 0;
    }

}