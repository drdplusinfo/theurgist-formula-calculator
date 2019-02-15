<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Armaments\Armors;

use DrdPlus\Codes\Armaments\ArmorCode;
use DrdPlus\Tables\Armaments\Exceptions\UnknownArmor;
use DrdPlus\Tables\Armaments\Partials\AbstractArmamentsTable;
use DrdPlus\Tables\Armaments\Partials\UnwieldyTable;
use Granam\String\StringInterface;
use Granam\Tools\ValueDescriber;

abstract class AbstractArmorsTable extends AbstractArmamentsTable implements UnwieldyTable
{

    public const PROTECTION = 'protection';

    private $customArmors = [];

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
        ];
    }

    /**
     * @param string|StringInterface $armorCode
     * @return int|false
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownArmor
     */
    public function getRequiredStrengthOf($armorCode)
    {
        return $this->getValueFor($armorCode, self::REQUIRED_STRENGTH);
    }

    /**
     * @param string|StringInterface $armorCode
     * @param $valueName
     * @return bool|float|int|string
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownArmor
     */
    protected function getValueFor($armorCode, string $valueName)
    {
        try {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $this->getValue([$armorCode], $valueName);
        } catch (\DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound $exception) {
            throw new UnknownArmor(
                'Unknown armor code: ' . ValueDescriber::describe($armorCode)
            );
        }
    }

    /**
     * @param string|StringInterface $armorCode
     * @return int
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownArmor
     */
    public function getRestrictionOf($armorCode): int
    {
        return $this->getValueFor($armorCode, self::RESTRICTION);
    }

    /**
     * @param string|StringInterface $armorCode
     * @return int
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownArmor
     */
    public function getProtectionOf($armorCode): int
    {
        return $this->getValueFor($armorCode, self::PROTECTION);
    }

    /**
     * @param string|StringInterface $armorCode
     * @return float
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownArmor
     */
    public function getWeightOf($armorCode): float
    {
        return $this->getValueFor($armorCode, self::WEIGHT);
    }

    public function getIndexedValues(): array
    {
        $indexedValues = parent::getIndexedValues();

        return array_merge($indexedValues, $this->customArmors[static::class] ?? []);
    }

    /**
     * @param ArmorCode $armorCode
     * @param array $newParameters
     * @return bool
     * @throws \DrdPlus\Tables\Armaments\Armors\Exceptions\DifferentArmorPartIsUnderSameName
     */
    protected function addCustomArmor(ArmorCode $armorCode, array $newParameters): bool
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $previousParameters = $this->findRow($armorCode);
        if ($previousParameters) {
            if ($newParameters === $previousParameters) {
                return false;
            }
            throw new Exceptions\DifferentArmorPartIsUnderSameName(
                "New armor part {$armorCode} can not be added as there is already an armor under same name"
                . ' but with different parameters: '
                . var_export(array_diff_assoc($previousParameters, $newParameters), true)
            );
        }
        $this->customArmors[static::class][$armorCode->getValue()] = $newParameters;

        return true;
    }
}