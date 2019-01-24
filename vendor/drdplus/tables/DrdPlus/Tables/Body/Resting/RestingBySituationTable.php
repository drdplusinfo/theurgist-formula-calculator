<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\Body\Resting;

use DrdPlus\Tables\Partials\AbstractFileTableWithPercents;
use DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound;
use DrdPlus\Tables\Partials\Exceptions\UnexpectedPercents;
use Granam\Tools\ValueDescriber;

/**
 * See PPH page 118 right column, @link https://pph.drdplus.info/#tabulka_odpocinku_podle_situace
 */
class RestingBySituationTable extends AbstractFileTableWithPercents
{
    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/resting_by_situation.csv';
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
     * @param string $situationCode
     * @param RestingSituationPercents $restingSituationPercents
     * @return int
     * @throws \DrdPlus\Tables\Body\Resting\Exceptions\UnknownCodeOfRestingInfluence
     * @throws \DrdPlus\Tables\Body\Resting\Exceptions\UnexpectedRestingSituationPercents
     */
    public function getRestingMalusBySituation(string $situationCode, RestingSituationPercents $restingSituationPercents): int
    {
        try {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $this->getBonusBy($situationCode, $restingSituationPercents);
        } catch (RequiredRowNotFound $requiredRowDataNotFound) {
            throw new Exceptions\UnknownCodeOfRestingInfluence(
                'Unknown influence on healing code ' . ValueDescriber::describe($situationCode)
            );
        } catch (UnexpectedPercents $unexpectedPercents) {
            throw new Exceptions\UnexpectedRestingSituationPercents($unexpectedPercents->getMessage());
        }
    }

}