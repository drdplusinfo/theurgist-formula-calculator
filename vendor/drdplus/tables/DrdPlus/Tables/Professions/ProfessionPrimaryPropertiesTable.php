<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Professions;

use DrdPlus\Codes\ProfessionCode;
use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Tables\Partials\AbstractFileTable;
use DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound;
use Granam\Tools\ValueDescriber;

/**
 * See PPH page 129 right column, @link https://pph.drdplus.info/#tabulka_hlavnich_vlastnosti_povolani
 */
class ProfessionPrimaryPropertiesTable extends AbstractFileTable
{
    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/profession_primary_properties.csv';
    }

    public const FIRST_PRIMARY_PROPERTY = 'first_primary_property';
    public const SECOND_PRIMARY_PROPERTY = 'second_primary_property';

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [self::FIRST_PRIMARY_PROPERTY => self::STRING, self::SECOND_PRIMARY_PROPERTY => self::STRING];
    }

    public const PROFESSION = 'profession';

    /**
     * @return array|string[]
     */
    protected function getRowsHeader(): array
    {
        return [self::PROFESSION];
    }

    /**
     * @param ProfessionCode $professionCode
     * @return array|PropertyCode[]
     * @throws \DrdPlus\Tables\Professions\Exceptions\UnknownProfession
     */
    public function getPrimaryPropertiesOf(ProfessionCode $professionCode)
    {
        try {
            $primaryProperties = [];
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            foreach ($this->getRow($professionCode) as $primaryPropertyValue) {
                if ($primaryPropertyValue !== '') {
                    $primaryProperties[] = PropertyCode::getIt($primaryPropertyValue);
                }
            }

            return $primaryProperties;
        } catch (RequiredRowNotFound $requiredRowNotFound) {
            throw new Exceptions\UnknownProfession(
                'Given profession is not known: ' . ValueDescriber::describe($professionCode)
            );
        }
    }
}