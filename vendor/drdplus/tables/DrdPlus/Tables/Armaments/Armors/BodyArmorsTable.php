<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\Armaments\Armors;

use DrdPlus\Codes\Armaments\BodyArmorCode;
use DrdPlus\BaseProperties\Strength;
use DrdPlus\Tables\Measurements\Weight\Weight;
use Granam\Integer\PositiveInteger;
use Granam\String\StringInterface;

/**
 * See PPH page 90 left column, @link https://pph.drdplus.info/#tabulka_zbroji_a_prileb
 */
class BodyArmorsTable extends AbstractArmorsTable
{
    private $customBodyArmors = [];

    public const ROUNDS_TO_PUT_ON = 'rounds_to_put_on';

    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/body_armors.csv';
    }

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [
            self::REQUIRED_STRENGTH => self::INTEGER,
            self::RESTRICTION => self::INTEGER,
            self::PROTECTION => self::POSITIVE_INTEGER,
            self::WEIGHT => self::FLOAT,
            self::ROUNDS_TO_PUT_ON => self::POSITIVE_INTEGER,
        ];
    }

    public const BODY_ARMOR = 'body_armor';

    /**
     * @return array|string[]
     */
    protected function getRowsHeader(): array
    {
        return [self::BODY_ARMOR];
    }

    /**
     * @param string|StringInterface $armorCode
     * @return int
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownArmor
     */
    public function getRoundsToPutOnOf($armorCode): int
    {
        return $this->getValueFor($armorCode, self::ROUNDS_TO_PUT_ON);
    }

    public function getIndexedValues(): array
    {
        $indexedValues = parent::getIndexedValues();

        return array_merge($indexedValues, $this->customBodyArmors);
    }

    /**
     * @param BodyArmorCode $bodyArmorCode
     * @param Strength $requiredStrength
     * @param int $restriction
     * @param int $protection
     * @param Weight $weight
     * @param PositiveInteger $roundsToPutOn
     * @return bool
     * @throws \DrdPlus\Tables\Armaments\Armors\Exceptions\DifferentBodyArmorIsUnderSameName
     */
    public function addCustomBodyArmor(
        BodyArmorCode $bodyArmorCode,
        Strength $requiredStrength,
        int $restriction,
        int $protection,
        Weight $weight,
        PositiveInteger $roundsToPutOn
    ): bool
    {
        try {
            return $this->addCustomArmor(
                $bodyArmorCode,
                [
                    self::REQUIRED_STRENGTH => $requiredStrength->getValue(),
                    self::RESTRICTION => $restriction,
                    self::PROTECTION => $protection,
                    self::WEIGHT => $weight->getKilograms(),
                    self::ROUNDS_TO_PUT_ON => $roundsToPutOn->getValue(),
                ]
            );
        } catch (Exceptions\DifferentArmorPartIsUnderSameName $differentArmorPartIsUnderSameName) {
            throw new Exceptions\DifferentBodyArmorIsUnderSameName(
                $differentArmorPartIsUnderSameName->getMessage(),
                $differentArmorPartIsUnderSameName->getCode(),
                $differentArmorPartIsUnderSameName
            );
        }
    }

}