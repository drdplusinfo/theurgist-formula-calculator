<?php declare(strict_types=1);

namespace DrdPlus\Tables\Theurgist\Partials;

use DrdPlus\Codes\Partials\AbstractCode;
use DrdPlus\Tables\Partials\AbstractTable;
use DrdPlus\Tables\Theurgist\Exceptions\InvalidValueForMutableParameter;
use DrdPlus\Tables\Theurgist\Exceptions\UnknownParameter;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameter;
use Granam\Integer\Tools\ToInteger;
use Granam\String\StringTools;
use Granam\Tools\ValueDescriber;

trait SanitizeMutableParameterChangesTrait
{
    /**
     * @param array|int[]|string[] $parameterChanges
     * @param array|string[] $possibleMutableParameterNames
     * @param AbstractCode $spellCode
     * @param AbstractTable $table
     * @return array|int[]
     * @throws \DrdPlus\Tables\Theurgist\Exceptions\InvalidValueForMutableParameter
     * @throws \DrdPlus\Tables\Theurgist\Exceptions\UnknownParameter
     */
    private function sanitizeMutableParameterChanges(
        array $parameterChanges,
        array $possibleMutableParameterNames,
        AbstractCode $spellCode,
        AbstractTable $table
    ): array
    {
        $sanitizedChanges = [];
        foreach ($possibleMutableParameterNames as $possibleMutableParameterName) {
            if (!\array_key_exists($possibleMutableParameterName, $parameterChanges)) {
                $sanitizedChanges[$possibleMutableParameterName] = 0; // no change
                continue;
            }
            try {
                $sanitizedValue = ToInteger::toInteger($parameterChanges[$possibleMutableParameterName]);
            } catch (\Granam\Integer\Tools\Exceptions\Exception $exception) {
                throw new InvalidValueForMutableParameter(
                    'Expected integer, got ' . ValueDescriber::describe($parameterChanges[$possibleMutableParameterName])
                    . ' for ' . $possibleMutableParameterName . ": '{$exception->getMessage()}'"
                );
            }
            /** like @see DemonsTable::getDemonCapacity() */
            $getParameter = StringTools::assembleGetterForName($possibleMutableParameterName);
            /** @var CastingParameter $parameterValue */
            $parameterValue = $table->$getParameter($spellCode);
            if ($parameterValue === null) {
                throw new UnknownParameter(
                    "Parameter {$possibleMutableParameterName} is not used for {$spellCode}"
                    . ', so given non-zero addition ' . ValueDescriber::describe($parameterChanges[$possibleMutableParameterName])
                    . ' is thrown away'
                );
            }
            $parameterChange = $sanitizedValue - $parameterValue->getDefaultValue();
            $sanitizedChanges[$possibleMutableParameterName] = $parameterChange;

            unset($parameterChanges[$possibleMutableParameterName]);
        }
        if (\count($parameterChanges) > 0) { // there are some remains
            throw new UnknownParameter(
                'Unexpected mutable parameter(s) [' . \implode(', ', array_keys($parameterChanges)) . ']. Expected only '
                . \implode(', ', $possibleMutableParameterNames)
            );
        }

        return $sanitizedChanges;
    }
}