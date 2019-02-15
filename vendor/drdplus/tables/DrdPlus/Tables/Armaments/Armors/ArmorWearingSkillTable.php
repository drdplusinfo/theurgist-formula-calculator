<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Armaments\Armors;

use DrdPlus\Tables\Armaments\MissingProtectiveArmamentSkill;
use DrdPlus\Tables\Armaments\Partials\AbstractArmamentSkillTable;
use Granam\Integer\PositiveInteger;

/**
 * See PPH page 147 left column, @link https://pph.drdplus.info/#noseni_zbroje
 */
class ArmorWearingSkillTable extends AbstractArmamentSkillTable implements MissingProtectiveArmamentSkill
{
    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/armor_wearing_skill.csv';
    }

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [self::RESTRICTION_BONUS => self::POSITIVE_INTEGER];
    }

    /**
     * @param int|PositiveInteger $skillRank
     * @return int
     * @throws \DrdPlus\Tables\Armaments\Partials\Exceptions\UnexpectedSkillRank
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     */
    public function getRestrictionBonusForSkillRank($skillRank): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getValueForSkillRank($skillRank, self::RESTRICTION_BONUS);
    }

}