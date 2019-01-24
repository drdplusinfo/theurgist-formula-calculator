<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\Armaments\Partials;

use DrdPlus\Tables\Partials\AbstractFileTable;
use DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound;
use Granam\Integer\IntegerInterface;
use Granam\Integer\Tools\ToInteger;
use Granam\Tools\ValueDescriber;

abstract class AbstractArmamentSkillTable extends AbstractFileTable
{
    public const SKILL_RANK = 'skill_rank';

    protected function getRowsHeader(): array
    {
        return [self::SKILL_RANK];
    }

    /**
     * @param int|IntegerInterface $skillRank
     * @param string $parameterName
     * @return int
     * @throws \DrdPlus\Tables\Armaments\Partials\Exceptions\UnexpectedSkillRank
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredColumnNotFound
     */
    protected function getValueForSkillRank($skillRank, $parameterName)
    {
        try {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $this->getValue([ToInteger::toInteger($skillRank)], $parameterName);
        } catch (RequiredRowNotFound $requiredRowNotFound) {
            throw new Exceptions\UnexpectedSkillRank(
                'Expected skill rank from 0 to 3, got ' . ValueDescriber::describe($skillRank)
            );
        }
    }
}