<?php
declare(strict_types=1);

namespace Granam\DiceRolls\Templates\RollOn;

use Granam\DiceRolls\DiceRoll;
use Granam\DiceRolls\Roller;
use Granam\DiceRolls\RollOn;
use Granam\Strict\Object\StrictObject;

abstract class AbstractRollOn extends StrictObject implements RollOn
{
    /** @var Roller */
    private $roller;

    public function __construct(Roller $roller)
    {
        $this->roller = $roller;
    }

    /**
     * @param int $sequenceNumberStart
     * @return DiceRoll[]
     */
    public function rollDices(int $sequenceNumberStart): array
    {
        return $this->roller->roll($sequenceNumberStart)->getDiceRolls();
    }

}