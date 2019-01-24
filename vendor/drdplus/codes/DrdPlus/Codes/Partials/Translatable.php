<?php
declare(strict_types=1); 

namespace DrdPlus\Codes\Partials;

interface Translatable
{
    /**
     * @param string $languageCode
     * @param int $amount
     * @return string
     */
    public function translateTo(string $languageCode, $amount = 1): string;
}