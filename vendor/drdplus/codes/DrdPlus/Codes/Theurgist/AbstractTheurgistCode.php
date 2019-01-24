<?php
declare(strict_types = 1);

namespace DrdPlus\Codes\Theurgist;

use DrdPlus\Codes\Partials\AbstractCode;

abstract class AbstractTheurgistCode extends AbstractCode
{
    /**
     * @param string $languageCode
     * @return string
     */
    public function translateTo(string $languageCode): string
    {
        $code = $this->getValue();
        $translations = $this->getTranslations($languageCode);
        if (\array_key_exists($code, $translations)) {
            return $translations[$code];
        }
        if ($languageCode === 'en') {
            return \str_replace('_', ' ', $code); // just replacing underscores by spaces
        }
        \trigger_error(
            "Missing translation for value '{$code}' and language '{$languageCode}', english will be used instead",
            E_USER_WARNING
        );
        $translations = $this->getTranslations('en');
        if (\array_key_exists($code, $translations)) {
            return $translations[$code];
        }

        return \str_replace('_', ' ', $code); // just replacing underscores by spaces
    }

    /**
     * @param string $languageCode
     * @return array|\string[]
     */
    abstract protected function getTranslations(string $languageCode): array;
}