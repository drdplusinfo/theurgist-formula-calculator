<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\Armaments\Armors;

use DrdPlus\Codes\Armaments\HelmCode;
use DrdPlus\Properties\Base\Strength;
use DrdPlus\Tables\Measurements\Weight\Weight;

/**
 * See PPH page 90 left column, @link https://pph.drdplus.info/#tabulka_zbroji_a_prileb
 */
class HelmsTable extends AbstractArmorsTable
{
    private $customHelms = [];

    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/helms.csv';
    }

    public const HELM = 'helm';

    /**
     * @return array|string[]
     */
    protected function getRowsHeader(): array
    {
        return [self::HELM];
    }

    public function getIndexedValues(): array
    {
        $indexedValues = parent::getIndexedValues();

        return array_merge($indexedValues, $this->customHelms);
    }

    /**
     * @param HelmCode $helmCode
     * @param Strength $requiredStrength
     * @param int $restriction
     * @param int $protection
     * @param Weight $weight
     * @return bool
     * @throws \DrdPlus\Tables\Armaments\Armors\Exceptions\DifferentHelmIsUnderSameName
     */
    public function addCustomHelm(
        HelmCode $helmCode,
        Strength $requiredStrength,
        int $restriction,
        int $protection,
        Weight $weight
    ): bool
    {
        try {
            return $this->addCustomArmor(
                $helmCode,
                [
                    self::REQUIRED_STRENGTH => $requiredStrength->getValue(),
                    self::RESTRICTION => $restriction,
                    self::PROTECTION => $protection,
                    self::WEIGHT => $weight->getKilograms(),
                ]
            );
        } catch (Exceptions\DifferentArmorPartIsUnderSameName $differentArmorPartIsUnderSameName) {
            throw new Exceptions\DifferentHelmIsUnderSameName(
                $differentArmorPartIsUnderSameName->getMessage(),
                $differentArmorPartIsUnderSameName->getCode(),
                $differentArmorPartIsUnderSameName
            );
        }
    }
}