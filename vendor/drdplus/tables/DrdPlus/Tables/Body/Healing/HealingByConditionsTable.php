<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Body\Healing;

use DrdPlus\Tables\Partials\AbstractFileTableWithPercents;
use DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound;
use DrdPlus\Tables\Partials\Exceptions\UnexpectedPercents;
use Granam\Tools\ValueDescriber;

/**
 * See PPH page 81 left column top, @link https://pph.drdplus.info/#tabulka_leceni_podle_podminek
 */
class HealingByConditionsTable extends AbstractFileTableWithPercents
{
    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/healing_by_conditions.csv';
    }

    public const SITUATION = 'situation';

    /**
     * @return array|string[]
     */
    protected function getRowsHeader(): array
    {
        return [self::SITUATION];
    }

    /**
     * @param string $conditionsCode
     * @param HealingConditionsPercents $healingConditionsPercents
     * @return int
     * @throws \DrdPlus\Tables\Body\Healing\Exceptions\UnknownCodeOfHealingInfluence
     * @throws \DrdPlus\Tables\Body\Healing\Exceptions\UnexpectedHealingConditionsPercents
     */
    public function getHealingBonusByConditions(string $conditionsCode, HealingConditionsPercents $healingConditionsPercents): int
    {
        try {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $this->getBonusBy($conditionsCode, $healingConditionsPercents);
        } catch (RequiredRowNotFound $requiredRowDataNotFound) {
            throw new Exceptions\UnknownCodeOfHealingInfluence(
                'Unknown influence on healing code ' . ValueDescriber::describe($conditionsCode)
            );
        } catch (UnexpectedPercents $unexpectedPercents) {
            throw new Exceptions\UnexpectedHealingConditionsPercents($unexpectedPercents->getMessage());
        }
    }

}