<?php
declare(strict_types=1);

namespace DrdPlus\Codes\Partials;

use Granam\Tools\ValueDescriber;

abstract class TranslatableExtendableCode extends TranslatableCode
{
    private static $customValues = [];
    private static $customCodeTranslations = [];

    public static function getPossibleValues(): array
    {
        return \array_merge(static::getDefaultValues(), static::getCustomValues());
    }

    /**
     * OVERLOAD this
     *
     * @return array
     */
    protected static function getDefaultValues(): array
    {
        return parent::getPossibleValues();
    }

    protected static function getCustomValues(): array
    {
        return self::$customValues[static::class] ?? [];
    }

    /**
     * @param string $requiredLanguageCode
     * @return array|string[]
     */
    protected function getTranslations(string $requiredLanguageCode): array
    {
        if ((self::$translations[static::class] ?? null) === null) {
            $translations = self::$customCodeTranslations[static::class] ?? [];
            if (\count($translations) === 0) {
                $translations = $this->fetchTranslations();
            } else {
                foreach ($this->fetchTranslations() as $languageCode => $languageTranslations) {
                    /** @var array $languageTranslations */
                    foreach ($languageTranslations as $codeValue => $codeTranslations) {
                        // child translations can overwrite custom translations
                        $translations[$languageCode][$codeValue] = $codeTranslations;
                    }
                }
            }
            self::$translations[static::class] = $translations;
        }

        return parent::getTranslations($requiredLanguageCode);
    }

    /**
     * @param string $newValue
     * @param array $translations
     * @return bool
     * @throws \DrdPlus\Codes\Partials\Exceptions\InvalidLanguageCode
     * @throws \DrdPlus\Codes\Partials\Exceptions\UnknownTranslationPlural
     * @throws \DrdPlus\Codes\Partials\Exceptions\InvalidTranslationFormat
     */
    protected static function addNewCode(string $newValue, array $translations): bool
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        if (\in_array($newValue, static::getPossibleValues(), true)) {
            return false;
        }
        self::$customValues[static::class][] = $newValue;
        self::checkTranslationsFormat($translations);
        foreach ($translations as $languageCode => $languageTranslations) {
            self::$customCodeTranslations[static::class][$languageCode][$newValue] = $languageTranslations;
        }
        if ((self::$translations[static::class] ?? null) !== null) {
            foreach ($translations as $languageCode => $languageTranslations) {
                self::$translations[static::class][$languageCode][$newValue] = $languageTranslations;
            }
        }

        return true;
    }

    /**
     * @param array $translations
     * @throws \DrdPlus\Codes\Partials\Exceptions\InvalidLanguageCode
     * @throws \DrdPlus\Codes\Partials\Exceptions\InvalidTranslationFormat
     * @throws \DrdPlus\Codes\Partials\Exceptions\UnknownTranslationPlural
     */
    private static function checkTranslationsFormat(array $translations): void
    {
        /**
         * @var string $languageCode
         * @var array|string[] $languageTranslations
         */
        foreach ($translations as $languageCode => $languageTranslations) {
            if (!\preg_match('~^[[:alpha:]]{2}$~', $languageCode)) {
                throw new Exceptions\InvalidLanguageCode(
                    'Code of language used for custom code translation should be 2-char string, got ' .
                    \var_export($languageCode, true)
                );
            }
            if (!\is_array($languageTranslations)) {
                throw new Exceptions\InvalidTranslationFormat(
                    'Expected array of translations for singular and plural, got '
                    . ValueDescriber::describe($languageTranslations) . ' for language ' . $languageCode
                );
            }
            foreach ($languageTranslations as $plural => $translation) {
                if (!\in_array($plural, [self::$ONE, self::$FEW, self::$FEW_DECIMAL, self::$MANY], true)) {
                    throw new Exceptions\UnknownTranslationPlural(
                        'Expected one of ' . \implode(',', [self::$ONE, self::$FEW, self::$FEW_DECIMAL, self::$MANY, true])
                        . ', got ' . \var_export($plural, true)
                    );
                }
                if (!\is_string($translation) || $translation === '') {
                    throw new Exceptions\InvalidTranslationFormat(
                        'Expected non-empty string, got ' . ValueDescriber::describe($translation)
                    );
                }
            }
        }
    }
}