<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Theurgist\Spells;

use DrdPlus\Tables\Partials\AbstractFileTable;
use DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound;
use DrdPlus\Codes\Theurgist\FormulaCode;
use DrdPlus\Codes\Theurgist\ModifierCode;
use DrdPlus\Codes\Theurgist\ProfileCode;

/**
 * @link https://theurg.drdplus.info/#tabulka_formuli
 */
class ProfilesTable extends AbstractFileTable
{
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/profiles.csv';
    }

    public const MODIFIERS = 'modifiers';
    public const FORMULAS = 'formulas';

    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [
            self::MODIFIERS => self::ARRAY,
            self::FORMULAS => self::ARRAY,
        ];
    }

    public const PROFILE = 'profile';

    protected function getRowsHeader(): array
    {
        return [
            self::PROFILE,
        ];
    }

    /**
     * @param ProfileCode $profileCode
     * @return array|ModifierCode[]
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\UnknownProfileToGetModifiersFor
     */
    public function getModifiersForProfile(ProfileCode $profileCode): array
    {
        try {
            return \array_map(
                function (string $modifierValue) {
                    return ModifierCode::getIt($modifierValue);
                },
                $this->getValue($profileCode, self::MODIFIERS)
            );
        } catch (RequiredRowNotFound $requiredRowNotFound) {
            throw new Exceptions\UnknownProfileToGetModifiersFor("Given profile code '{$profileCode}' is unknown");
        }
    }

    /**
     * @param ProfileCode $profileCode
     * @return array|FormulaCode[]
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\UnknownProfileToGetFormulasFor
     */
    public function getFormulasForProfile(ProfileCode $profileCode): array
    {
        try {
            return \array_map(
                function (string $formulaValue) {
                    return FormulaCode::getIt($formulaValue);
                },
                $this->getValue($profileCode, self::FORMULAS)
            );
        } catch (RequiredRowNotFound $requiredRowNotFound) {
            throw new Exceptions\UnknownProfileToGetFormulasFor("Given profile code '{$profileCode}' is unknown");
        }
    }

}