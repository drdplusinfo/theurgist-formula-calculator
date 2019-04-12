<?php
declare(strict_types = 1);

namespace DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials;

use Granam\String\StringTools;

trait GetParameterNameTrait
{

    protected function getParameterName(): string
    {
        $snakeCaseBaseName = StringTools::camelCaseToSnakeCasedBasename(static::class);
        return str_replace('_', ' ', $snakeCaseBaseName);
    }
}