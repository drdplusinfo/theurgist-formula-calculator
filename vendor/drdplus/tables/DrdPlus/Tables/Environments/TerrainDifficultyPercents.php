<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Environments;

use DrdPlus\Tables\Partials\Exceptions\UnexpectedPercents;
use DrdPlus\Tables\Partials\Percents;
use Granam\Integer\PositiveInteger;
use Granam\Tools\ValueDescriber;

class TerrainDifficultyPercents extends Percents
{

    /**
     * @param int|PositiveInteger $value
     * @throws \DrdPlus\Tables\Environments\Exceptions\UnexpectedDifficultyPercents
     */
    public function __construct($value)
    {
        try {
            parent::__construct($value);
        } catch (UnexpectedPercents $unexpectedPercents) {
            throw new Exceptions\UnexpectedDifficultyPercents($unexpectedPercents->getMessage());
        }
        if ($this->getValue() > 100) {
            throw new Exceptions\UnexpectedDifficultyPercents(
                'Percents can be from zero to one hundred, got ' . ValueDescriber::describe($value)
            );
        }
    }
}