<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\Measurements\Partials;

use DrdPlus\Tables\Measurements\MeasurementWithBonus;
use DrdPlus\Tables\Measurements\Exceptions\BonusAlreadyPaired;
use DrdPlus\Tables\Measurements\Exceptions\DataFromFileAreCorrupted;
use DrdPlus\Tables\Measurements\Exceptions\DataRowsAreMissingInFile;
use DrdPlus\Tables\Measurements\Exceptions\FileCanNotBeRead;
use DrdPlus\Tables\Measurements\Exceptions\FileIsEmpty;
use DrdPlus\Tables\Measurements\Exceptions\UnexpectedChanceNotation;
use DrdPlus\Tables\Measurements\Exceptions\UnknownUnit;
use DrdPlus\Tables\Measurements\Tools\EvaluatorInterface;
use DrdPlus\Tables\Partials\AbstractTable;
use Granam\Float\Tools\ToFloat;
use Granam\Integer\Tools\ToInteger;

/**
 * Note: every file-table can create Bonus as well as Measurement
 */
abstract class AbstractMeasurementFileTable extends AbstractTable
{

    /**
     * @var string[][]
     */
    private $indexedValues;
    /**
     * @var EvaluatorInterface
     */
    private $evaluator;

    public function __construct(EvaluatorInterface $evaluator)
    {
        $this->evaluator = $evaluator;
    }

    public function getIndexedValues(): array
    {
        if ($this->indexedValues === null) {
            try {
                $this->loadData();
            } catch (\DrdPlus\Tables\Measurements\Exceptions\Exception $loadingException) {
                throw new Exceptions\LoadingDataFailed(
                    $loadingException->getMessage(),
                    $loadingException->getCode(),
                    $loadingException
                );
            }
        }

        return $this->indexedValues;
    }

    /**
     * @return array|string[][]
     */
    protected function getRowsHeader(): array
    {
        return [['bonus']];
    }

    /**
     * @return array|\string[]
     */
    protected function getColumnsHeader(): array
    {
        return $this->getExpectedDataHeader();
    }

    /**
     * @return \string[]
     */
    abstract protected function getExpectedDataHeader(): array;

    /**
     * @return string
     */
    abstract protected function getDataFileName(): string;

    /**
     * @param int $bonusValue
     * @return AbstractBonus
     */
    abstract protected function createBonus(int $bonusValue): AbstractBonus;

    /**
     * @param float $value
     * @param string $unit
     * @return MeasurementWithBonus
     */
    abstract protected function convertToMeasurement(float $value, string $unit): MeasurementWithBonus;

    /**
     * @throws \DrdPlus\Tables\Measurements\Exceptions\FileCanNotBeRead
     * @throws \DrdPlus\Tables\Measurements\Exceptions\FileIsEmpty
     * @throws \DrdPlus\Tables\Measurements\Exceptions\DataFromFileAreCorrupted
     * @throws \DrdPlus\Tables\Measurements\Exceptions\BonusAlreadyPaired
     * @throws \DrdPlus\Tables\Measurements\Exceptions\DataRowsAreMissingInFile
     */
    private function loadData(): void
    {
        $rawData = $this->fetchDataFromFile($this->getDataFileName());
        $indexedValues = $this->normalizeAndIndex($rawData);
        $this->indexedValues = $indexedValues;
    }

    /**
     * @param string $dataSourceFile
     * @return array
     * @throws \DrdPlus\Tables\Measurements\Exceptions\FileCanNotBeRead
     * @throws \DrdPlus\Tables\Measurements\Exceptions\FileIsEmpty
     */
    private function fetchDataFromFile(string $dataSourceFile): array
    {
        $resource = \fopen($dataSourceFile, 'rb');
        if (!$resource) {
            throw new FileCanNotBeRead("File with table data could not be read from $dataSourceFile");
        }
        $data = [];
        do {
            $row = \fgetcsv($resource);
            if ($row !== false && \count($row)) { // otherwise skipp empty row
                $data[] = $row;
            }
        } while (\is_array($row));

        if (!$data) {
            throw new FileIsEmpty("No data have been read from $dataSourceFile");
        }

        return $data;
    }

    /**
     * @param array $data
     * @return array
     * @throws \DrdPlus\Tables\Measurements\Exceptions\DataFromFileAreCorrupted
     * @throws \DrdPlus\Tables\Measurements\Exceptions\BonusAlreadyPaired
     * @throws \DrdPlus\Tables\Measurements\Exceptions\DataRowsAreMissingInFile
     */
    protected function normalizeAndIndex(array $data): array
    {
        $expectedHeader = \array_merge(['bonus'], $this->getExpectedDataHeader());
        if (!\array_key_exists(0, $data) || $data[0] !== $expectedHeader) {
            throw new DataFromFileAreCorrupted(
                "Data file is corrupted. Expected header with '" . \implode(',', $expectedHeader) . "'"
                . ", got '" . \implode(',', $data[0]) . "'"
            );
        }
        $indexed = [];
        unset($data[0]); // removing human header
        foreach ($data as $row) {
            if (\count($row) > 0) {
                $formattedRow = $this->formatRow($row, $expectedHeader);
                if (\array_key_exists(key($formattedRow), $indexed)) {
                    throw new BonusAlreadyPaired(
                        'Bonus ' . \key($formattedRow) . ' is already paired with value(s) ' . \implode(',', $indexed[\key($formattedRow)])
                        . ', got ' . \implode(',', \current($formattedRow))
                    );
                }
                $indexed[key($formattedRow)] = \current($formattedRow);
            }
        }
        if (\count($indexed) === 0) {
            throw new DataRowsAreMissingInFile(
                'Data file is empty. Expected at least single row with values (header excluded)'
            );
        }

        return $indexed;
    }

    /**
     * @param array|string[] $row
     * @param array|string[] $expectedHeader
     * @return array|string[]
     * @throws \DrdPlus\Tables\Measurements\Exceptions\DataFromFileAreCorrupted
     */
    private function formatRow(array $row, array $expectedHeader): array
    {
        $indexedValues = \array_combine($expectedHeader, $row);
        try {
            $bonus = $this->parseBonus($indexedValues['bonus']);
            unset($indexedValues['bonus']); // left values only
            $indexedRow = [$bonus => []];
            foreach ($indexedValues as $index => $value) {
                $value = $this->parseValue($value);
                if ($value === false) { // skipping empty value
                    continue;
                }
                $indexedRow[$bonus][$index] = $value;
            }
        } catch (\Granam\Number\Tools\Exceptions\Exception $conversionException) {
            throw new DataFromFileAreCorrupted(
                $conversionException->getMessage(), $conversionException->getCode(), $conversionException
            );
        }

        return $indexedRow;
    }

    /**
     * @param string $value
     * @return int
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     */
    private function parseBonus(string $value): int
    {
        return ToInteger::toInteger($this->parseNumber($value));
    }

    private function parseNumber(string $value): string
    {
        return \str_replace(
            ['−' /* from ASCII 226 */, ','], // unified minus sign and float format (decimal delimiter)
            ['-' /* to ASCII 45 */, '.'],
            $value
        );
    }

    /**
     * @param string $value
     * @return bool|float|string
     * @throws \Granam\Float\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Float\Tools\Exceptions\ValueLostOnCast
     */
    private function parseValue(string $value)
    {
        $value = \trim($value);
        if ($value === '') {
            return false;
        }
        if ($this->isItDiceRollChance($value)) { // dice chance bonus, like 1/6
            return $value; // string
        }

        return ToFloat::toFloat($this->parseNumber($value));
    }

    /**
     * @param mixed $value
     * @return bool
     */
    private function isItDiceRollChance($value): bool
    {
        return \preg_match('~^\d+/\d+$~', (string)$value) > 0;
    }

    /**
     * @param AbstractBonus $bonus
     * @param string|null $wantedUnit
     * @return MeasurementWithBonus
     * @throws \DrdPlus\Tables\Measurements\Partials\Exceptions\UnknownBonus
     * @throws \DrdPlus\Tables\Measurements\Exceptions\UnknownUnit
     * @throws \DrdPlus\Tables\Measurements\Partials\Exceptions\RequestedDataOutOfTableRange
     */
    protected function toMeasurement(AbstractBonus $bonus, string $wantedUnit = null): MeasurementWithBonus
    {
        $bonusValue = $bonus->getValue();
        $this->guardBonusExisting($bonusValue);
        $wantedUnit = $this->determineUnit($bonusValue, $wantedUnit);
        $rawValue = $this->getIndexedValues()[$bonusValue][$wantedUnit] ?? null;
        if ($rawValue === null) {
            throw new Exceptions\RequestedDataOutOfTableRange(
                "Can not convert bonus {$bonusValue} to measurement value in unit '{$wantedUnit}'"
            );
        }
        $wantedValue = $this->evaluate($rawValue);

        return $this->convertToMeasurement($wantedValue, $wantedUnit);
    }

    /**
     * @param int $bonusValue
     * @throws \DrdPlus\Tables\Measurements\Partials\Exceptions\UnknownBonus
     */
    private function guardBonusExisting(int $bonusValue): void
    {
        if (!\array_key_exists($bonusValue, $this->getIndexedValues())) {
            throw new Exceptions\UnknownBonus("Value to bonus {$bonusValue} is not defined.");
        }
    }

    /**
     * @param int $bonusValue
     * @param string|null $wantedUnit
     * @return mixed
     * @throws \DrdPlus\Tables\Measurements\Exceptions\UnknownUnit
     * @throws \DrdPlus\Tables\Measurements\Partials\Exceptions\UnknownBonus
     */
    private function determineUnit(int $bonusValue, string $wantedUnit = null): string
    {
        if ($wantedUnit === null) {
            $this->guardBonusExisting($bonusValue);
            /** @noinspection CallableParameterUseCaseInTypeContextInspection */
            $wantedUnit = \key($this->getIndexedValues()[$bonusValue]);
        } else {
            $this->checkUnitExistence($wantedUnit);
        }

        return $wantedUnit;
    }

    /**
     * @param string $unit
     * @throws \DrdPlus\Tables\Measurements\Exceptions\UnknownUnit
     */
    protected function checkUnitExistence(string $unit): void
    {
        if (!\in_array($unit, $this->getExpectedDataHeader(), true)) {
            throw new UnknownUnit(
                'Expected one of units ' . implode(',', $this->getExpectedDataHeader()) . ", got $unit"
            );
        }
    }

    private function hasValueByBonusValueAndUnit(int $bonusValue, string $wantedUnit): bool
    {
        return ($this->getIndexedValues()[$bonusValue][$wantedUnit] ?? null) !== null;
    }

    private function evaluate($rawValue): float
    {
        if (\is_float($rawValue)) {
            return $rawValue;
        }

        return $this->evaluator->evaluate($this->parseMaxRollToGetValue($rawValue));
    }

    /**
     * @param string $chance
     * @return int
     * @throws \DrdPlus\Tables\Measurements\Exceptions\UnexpectedChanceNotation
     */
    private function parseMaxRollToGetValue(string $chance): int
    {
        $chanceParts = \explode('/', $chance);
        if (!\array_key_exists(0, $chanceParts) || !\array_key_exists(1, $chanceParts) || (int)$chanceParts[0] < 0 || (int)$chanceParts[0] > 6
            || (int)$chanceParts[1] !== 6
        ) {
            throw new UnexpectedChanceNotation("Expected only 0..6/6 chance, got $chance");
        }

        return (int)$chanceParts[0];
    }

    /**
     * @param AbstractBonus $bonus
     * @param string|null $wantedUnit
     * @return bool
     * @throws \DrdPlus\Tables\Measurements\Exceptions\UnknownUnit
     * @throws \DrdPlus\Tables\Measurements\Partials\Exceptions\UnknownBonus
     */
    protected function hasMeasurementFor(AbstractBonus $bonus, string $wantedUnit = null): bool
    {
        $bonusValue = $bonus->getValue();
        $wantedUnit = $this->determineUnit($bonusValue, $wantedUnit);

        return $this->hasValueByBonusValueAndUnit($bonusValue, $wantedUnit);
    }

    protected function measurementToBonus(MeasurementWithBonus $measurement): AbstractBonus
    {
        return $this->createBonus($this->determineBonusValue($measurement));
    }

    /**
     * @param MeasurementWithBonus $measurement
     * @return int
     * @throws \DrdPlus\Tables\Measurements\Partials\Exceptions\RequestedDataOutOfTableRange
     */
    private function determineBonusValue(MeasurementWithBonus $measurement): int
    {
        $finds = $this->getBonusMatchingOrClosestTo($measurement);
        if (\is_int($finds)) {
            return $finds; // we found the bonus by value exact match
        }

        return $this->getBonusClosestTo($measurement->getValue(), $finds['lower'], $finds['higher']);
    }

    /**
     * @param MeasurementWithBonus $measurement
     * @return array|int|string
     * @throws \DrdPlus\Tables\Measurements\Partials\Exceptions\RequestedDataOutOfTableRange
     */
    private function getBonusMatchingOrClosestTo(MeasurementWithBonus $measurement)
    {
        $searchedValue = ToFloat::toFloat($measurement->getValue());
        $searchedUnit = $measurement->getUnit();
        $closest = ['lower' => [], 'higher' => []]; // value to bonuses
        foreach ($this->getIndexedValues() as $bonus => $relatedValues) {
            if (!\array_key_exists($searchedUnit, $relatedValues)) { // current row doesn't have required unit
                continue;
            }
            $relatedValue = $relatedValues[$searchedUnit];
            if ($relatedValue === $searchedValue) {
                return $bonus; // we have found exact match
            }
            if ($this->isItDiceRollChance($relatedValue)) {
                continue; // dice roll chance fractions are skipped (example '2/6')
            }
            $stringRelatedValue = (string)$relatedValue; // because PHP is silently converting float to int
            if ($searchedValue > $relatedValue) {
                if (\count($closest['lower']) === 0 || \key($closest['lower']) < $stringRelatedValue) {
                    $closest['lower'] = [$stringRelatedValue => [$bonus]]; // new value to [bonus] pair
                } elseif (\count($closest['lower']) > 0 && \key($closest['lower']) === $stringRelatedValue) {
                    $closest['lower'][$stringRelatedValue][] = $bonus; // adding bonus for same value
                }
            } elseif ($searchedValue < $relatedValue) {
                if (\count($closest['higher']) === 0 || \key($closest['higher']) > $stringRelatedValue) {
                    $closest['higher'] = [$stringRelatedValue => [$bonus]]; // new value to bonus pair
                } elseif (\count($closest['higher']) > 0 && \key($closest['higher']) === $stringRelatedValue) {
                    $closest['higher'][$stringRelatedValue][] = $bonus; // adding bonus for same value
                }
            }
        }

        if (\count($closest['lower']) === 0 || \count($closest['higher']) === 0) {
            throw new Exceptions\RequestedDataOutOfTableRange(
                "$searchedValue '$searchedUnit' is out of " . static::class . ' values.'
            );
        }

        return $closest;
    }

    private function getBonusClosestTo(float $searchedValue, array $closestLower, array $closestHigher): int
    {
        $closerValue = $this->getCloserValue(
            $searchedValue,
            // because float keys are encoded as string (otherwise PHP will cast them silently to int when used as array keys)
            ToFloat::toFloat(\key($closestLower)),
            ToFloat::toFloat(\key($closestHigher))
        );
        if ($closerValue !== false) {
            if (\array_key_exists((string)$closerValue, $closestLower)) {
                $bonuses = $closestLower[(string)$closerValue];
            } else {
                $bonuses = $closestHigher[(string)$closerValue];
            }

            // matched single table-value, maybe with more bonuses, the lowest bonus should be taken
            return \min($bonuses); // PPH page 11, right column
        }
        // both table border-values are equally close to the value, we will choose from bonuses of both borders
        $bonuses = \array_merge(
            \count($closestLower) > 0
                ? \current($closestLower)
                : []
            ,
            \count($closestHigher) > 0
                ? \current($closestHigher)
                : []
        );

        // matched two table-values, more bonuses for sure, the highest bonus should be taken
        return \max($bonuses); // PPH page 11, right column
    }

    /**
     * @param float $toValue
     * @param float $firstValue
     * @param float $secondValue
     * @return number|false
     */
    private function getCloserValue(float $toValue, float $firstValue, float $secondValue)
    {
        $firstDifference = $toValue - $firstValue;
        $secondDifference = $toValue - $secondValue;
        if (\abs($firstDifference) < \abs($secondDifference)) {
            return $firstValue;
        }
        if (\abs($secondDifference) < \abs($firstDifference)) {
            return $secondValue;
        }

        return false; // differences are equal
    }

}