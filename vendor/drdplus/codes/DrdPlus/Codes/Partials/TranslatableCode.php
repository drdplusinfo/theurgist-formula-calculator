<?php
declare(strict_types=1);

namespace DrdPlus\Codes\Partials;

use Granam\Number\NumberInterface;
use Granam\Number\Tools\ToNumber;

abstract class TranslatableCode extends AbstractCode implements Translatable
{
    // protected static values are more comfortable then protected constants in children classes
    protected static $ONE = 'one';
    protected static $FEW = 'few';
    protected static $FEW_DECIMAL = 'few_decimal';
    protected static $MANY = 'many';
    protected static $CS = 'cs';
    protected static $EN = 'en';

    protected static $translations = [];

    /**
     * @param string $languageCode
     * @param int|NumberInterface $amount
     * @return string
     */
    public function translateTo(string $languageCode, $amount = 1): string
    {
        $code = $this->getValue();
        $translations = $this->getTranslations($languageCode);
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $plural = $this->convertAmountToPlural(ToNumber::toNumber($amount));
        if (($translations[$code][$plural] ?? null) !== null) {
            return $translations[$code][$plural];
        }
        if ($plural === self::$FEW_DECIMAL) {
            $plural = self::$FEW;
            if (($translations[$code][$plural] ?? null) !== null) {
                return $translations[$code][$plural];
            }
        }
        if ($plural !== self::$ONE) {
            $plural = self::$ONE;
            if (($translations[$code][$plural] ?? null) !== null) {
                return $translations[$code][$plural];
            }
        }
        if ($languageCode === 'en') {
            return str_replace('_', ' ', $code); // just replacing underscores by spaces
        }
        \trigger_error(
            "Missing translation for value '{$code}', language '{$languageCode}' and plural '{$plural}' for code "
            . static::class . ', english will be used instead',
            E_USER_WARNING
        );
        $translations = $this->getTranslations('en');
        if (($translations[$code][$plural] ?? null) !== null) {
            return $translations[$code][$plural]; // explicit english translation
        }

        return str_replace('_', ' ', $code); // just replacing underscores by spaces
    }

    /**
     * @param float|int $amount
     * @return string
     */
    private function convertAmountToPlural($amount): string
    {
        $amount = \abs($amount);
        if ((float)$amount === 1.0) {
            return self::$ONE;
        }
        if ($amount < 5) {
            if (\strpos((string)$amount, '.') !== false) {
                return self::$FEW_DECIMAL;
            }

            return self::$FEW;
        }

        return self::$MANY;
    }

    /**
     * @param string $requiredLanguageCode
     * @return array|string[]
     */
    protected function getTranslations(string $requiredLanguageCode): array
    {
        if ((self::$translations[static::class] ?? null) === null) {
            self::$translations[static::class] = $this->fetchTranslations();
        }

        return self::$translations[static::class][$requiredLanguageCode] ?? [];
    }

    /**
     * @return array|string[][]
     */
    abstract protected function fetchTranslations(): array;
}