<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Armaments\Partials;

interface StrengthSanctionsInterface
{
    /**
     * @param int $missingStrength
     * @return bool
     */
    public function canUseIt(int $missingStrength): bool;
}