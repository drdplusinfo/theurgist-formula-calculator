<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\Partials;

use Granam\Boolean\Tools\ToBoolean;
use Granam\Float\Tools\ToFloat;
use Granam\Integer\Tools\ToInteger;
use Granam\Tools\ValueDescriber;

abstract class AbstractFileTable extends AbstractTable
{
    public const INTEGER = 'integer';
    public const POSITIVE_INTEGER = 'positive_integer';
    public const NEGATIVE_INTEGER = 'negative_integer';
    public const FLOAT = 'float';
    public const BOOLEAN = 'boolean';
    public const STRING = 'string';
    public const ARRAY = 'array';

    /** @var array|string[][]|string[][][] */
    private $indexedValues;

    /** @var array */
    private $normalizedExpectedColumnHeader;

    /** @var array */
    private $columnsHeader;

    /**
     * @return array|string[][]|string[][][]
     */
    public function getIndexedValues(): array
    {
        if ($this->indexedValues === null) {
            $this->loadData();
        }

        return $this->indexedValues;
    }

    /**
     * @return array|string[][]|string[][][]
     */
    protected function getColumnsHeader(): array
    {
        if ($this->columnsHeader === null) {
            $this->loadData();
        }

        return $this->columnsHeader;
    }

    private function loadData()
    {
        $rawData = $this->fetchDataFromFile($this->getDataFileName());
        $this->indexedValues = $this->normalizeAndIndex($rawData);
    }

    /** @return string */
    abstract protected function getDataFileName(): string;

    private function fetchDataFromFile(string $dataSourceFile): array
    {
        $resource = fopen($dataSourceFile, 'rb');
        if (!$resource) {
            throw new Exceptions\CanNotReadFile("File with table data could not be read from $dataSourceFile");
        }
        $data = [];
        do {
            $row = fgetcsv($resource);
            if ($row !== false && count($row) > 0) { // otherwise skip empty row
                $data[] = $row;
            }
        } while (is_array($row));

        if (!$data) {
            throw new Exceptions\NoDataFetched("No data have been read from $dataSourceFile");
        }

        return $data;
    }

    private function normalizeAndIndex(array $rawData): array
    {
        $rowsHeader = $this->parseRowsHeader($rawData);
        $this->columnsHeader = $this->parseColumnsHeader($rawData);
        $valuesWithoutColumnsHeader = $this->cutOffColumnsHeader($rawData);
        $valuesWithoutHeader = $this->cutOffRowsHeader($valuesWithoutColumnsHeader);
        $formattedValues = $this->formatValues($valuesWithoutHeader);
        $indexed = $this->indexValues($formattedValues, $rowsHeader, $this->columnsHeader);

        return $indexed;
    }

    private function cutOffRowsHeader(array $values): array
    {
        $rowHeadersUsedAsColumnHeaderAsWell = $this->getRowHeadersUsedAsColumnHeaderAsWell();
        $rowIndexes = array_keys($values);
        foreach ($rowIndexes as $rowIndex) {
            foreach ($this->getRowsHeader() as $columnIndexOfRowHeader => $rowHeaderName) {
                if (!in_array($rowHeaderName, $rowHeadersUsedAsColumnHeaderAsWell, true)) {
                    unset($values[$rowIndex][$columnIndexOfRowHeader]);
                }
            }
            // fixing sequence of number indexes ([1=>foo, 3=>bar] = [0=>foo, 1=>bar])
            $values[$rowIndex] = array_values($values[$rowIndex]);
        }

        return $values; // pure values without header
    }

    private function parseRowsHeader(array $data): array
    {
        $rowsHeaderNamesRow = $this->parseRowsHeaderNames($data);
        if (count($rowsHeaderNamesRow) === 0) {
            return [];
        }
        $rowsHeaderValues = []; // rows header values to data row index
        foreach ($data as $rowIndex => $dataRow) {
            if ($rowIndex === 0) {
                continue; // skipping header names
            }
            $rowsHeaderValuesPart = &$rowsHeaderValues;
            foreach ($rowsHeaderNamesRow as $dataColumnIndex => $headerName) {
                $headerValue = $dataRow[$dataColumnIndex];
                if (!isset($rowsHeaderValuesPart[$headerValue])) {
                    $rowsHeaderValuesPart[$headerValue] = [];
                }
                $rowsHeaderValuesPart = &$rowsHeaderValuesPart[$headerValue];
            }
            $rowsHeaderValuesPart = $rowIndex - 1; // because of gap by skipped first row
        }

        return $rowsHeaderValues;
    }

    private function parseRowsHeaderNames(array $rawData): array
    {
        $rowsHeaderNames = [];
        foreach ($this->getRowsHeader() as $expectedColumnIndex => $expectedHeaderValue) {
            $this->checkHeaderValue($rawData, $expectedColumnIndex, $expectedHeaderValue);
            $rowsHeaderNames[$expectedColumnIndex] = $expectedHeaderValue;
        }

        return $rowsHeaderNames;
    }

    private function checkHeaderValue(array $rawData, $columnIndex, $expectedHeaderValue)
    {
        if (!array_key_exists(0, $rawData) || !array_key_exists($columnIndex, $rawData[0])) {
            throw new Exceptions\DataAreCorrupted(
                "Missing header cell [$columnIndex] with expected value " . ValueDescriber::describe($expectedHeaderValue)
            );
        }
        if ($rawData[0][$columnIndex] !== $expectedHeaderValue) {
            throw new Exceptions\DataAreCorrupted(
                "Expected header with name '$expectedHeaderValue' on first row and column with index " . $columnIndex
                . ', got ' . ValueDescriber::describe($rawData[0][$columnIndex])
            );
        }
    }

    private function parseColumnsHeader(array $rawData): array
    {
        $columnsHeaderValues = [];
        $expectedColumnsHeader = $this->getNormalizedExpectedColumnsHeader(); // the very first rows of data
        // just header-column will be skipped, therefore row headers acting as column headers will be involved
        $indexShift = count($this->getRowsHeader()) - count($this->getRowHeadersUsedAsColumnHeaderAsWell());
        $expectedColumnIndexes = array_keys($expectedColumnsHeader);
        foreach ($expectedColumnIndexes as $expectedColumnIndex) {
            $expectedHeaderValue = $expectedColumnsHeader[$expectedColumnIndex]['value'];
            $rawDataColumnIndex = $expectedColumnIndex + $indexShift;
            $this->checkHeaderValue($rawData, $rawDataColumnIndex, $expectedHeaderValue);
            $columnsHeaderValues[$expectedColumnIndex] = $expectedHeaderValue;
        }

        return $columnsHeaderValues;
    }

    private function getRowHeadersUsedAsColumnHeaderAsWell(): array
    {
        $rowHeadersUsedAsColumnHeaderAsWell = [];
        foreach ($this->getRowsHeader() as $rowHeader) {
            foreach ($this->getNormalizedExpectedColumnsHeader() as $expectedColumnIndex) {
                if ($expectedColumnIndex['value'] === $rowHeader) {
                    $rowHeadersUsedAsColumnHeaderAsWell[] = $rowHeader;
                    break;
                }
            }
        }

        return $rowHeadersUsedAsColumnHeaderAsWell;
    }

    private function cutOffColumnsHeader(array $rawData): array
    {
        unset($rawData[0]);

        return array_values($rawData); // fixing row numeric indexes sequence ([1=>foo, 3=>bar] = [0=>foo, 1=>bar])
    }

    private function formatValues(array $data): array
    {
        return array_map(
            function (array $row) {
                return array_map(
                    function ($value, $columnIndex) {
                        return $this->parseRowValue($value, $columnIndex);
                    },
                    $row, array_keys($row)
                );
            },
            $data
        );
    }

    private function parseRowValue($value, $columnIndex)
    {
        return $this->normalizeValueForType($value, $this->getColumnType($columnIndex));
    }

    /**
     * @param $value
     * @param string $type
     * @return bool|float|int|string
     */
    private function normalizeValueForType($value, string $type)
    {
        $value = trim($value);
        switch ($type) {
            case self::BOOLEAN :
                /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
                return ToBoolean::toBoolean($value, false /* not strict */);
            case self::INTEGER :
                /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
                return $value === '' ? false : ToInteger::toInteger($this->normalizeMinus($value));
            case self::POSITIVE_INTEGER :
                /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
                return $value === '' ? false : ToInteger::toPositiveInteger($this->normalizeMinus($value));
            case self::NEGATIVE_INTEGER :
                /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
                return $value === '' ? false : ToInteger::toNegativeInteger($this->normalizeMinus($value));
            case self::FLOAT :
                /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
                return $value === '' ? false : ToFloat::toFloat($this->normalizeMinus($value));
            case self::ARRAY :
                /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
                return array_map(
                    function (string $item) {
                        return trim($item);
                    },
                    $value !== ''
                        ? explode(';', $value)
                        : []
                );
            default : // string
                return $value;
        }
    }

    private function normalizeMinus($value): string
    {
        return str_replace('−' /* ASCII 226 */, '-' /* ASCII 45 */, $value);
    }

    /**
     * @return array
     * @throws \DrdPlus\Tables\Partials\Exceptions\UnknownTypeForColumn
     * @throws \DrdPlus\Tables\Partials\Exceptions\ExpectedDataHeaderNamesToTypesAreEmpty
     */
    private function getNormalizedExpectedColumnsHeader(): array
    {
        if ($this->normalizedExpectedColumnHeader === null) {
            $this->normalizedExpectedColumnHeader = [];
            $columnIndex = 0;
            $expectedDataHeaderNamesToTypes = $this->getExpectedDataHeaderNamesToTypes();
            if (count($expectedDataHeaderNamesToTypes) === 0) {
                throw new Exceptions\ExpectedDataHeaderNamesToTypesAreEmpty(
                    'Expected at least one header for data mapped to its type, got nothing'
                );
            }
            foreach ($expectedDataHeaderNamesToTypes as $headerName => $columnScalarType) {
                $this->normalizedExpectedColumnHeader[$columnIndex++] = [
                    'value' => $headerName,
                    'type' => $this->normalizeType($columnScalarType),
                ];
            }
        }

        return $this->normalizedExpectedColumnHeader;
    }

    /** @return string[] */
    abstract protected function getExpectedDataHeaderNamesToTypes(): array;

    /**
     * @param string $type
     * @return string
     * @throws \DrdPlus\Tables\Partials\Exceptions\UnknownTypeForColumn
     */
    private function normalizeType(string $type): string
    {
        switch (strtolower($type)) {
            case self::INTEGER :
                return self::INTEGER;
            case self::POSITIVE_INTEGER :
                return self::POSITIVE_INTEGER;
            case self::NEGATIVE_INTEGER :
                return self::NEGATIVE_INTEGER;
            case self::BOOLEAN :
                return self::BOOLEAN;
            case self::FLOAT :
                return self::FLOAT;
            case self::STRING :
                return self::STRING;
            case self::ARRAY :
                return self::ARRAY;
            default :
                throw new Exceptions\UnknownTypeForColumn(
                    'Unknown type ' . ValueDescriber::describe($type)
                );
        }
    }

    /**
     * @param $columnIndex
     * @return mixed
     * @throws \DrdPlus\Tables\Partials\Exceptions\UnknownFetchedColumn
     * @throws \DrdPlus\Tables\Partials\Exceptions\UnknownTypeForColumn
     * @throws \DrdPlus\Tables\Partials\Exceptions\ExpectedDataHeaderNamesToTypesAreEmpty
     */
    private function getColumnType($columnIndex)
    {
        $header = $this->getNormalizedExpectedColumnsHeader();
        if (!array_key_exists($columnIndex, $header)) {
            throw new Exceptions\UnknownFetchedColumn(
                'Given column index ' . ValueDescriber::describe($columnIndex) . ' does not exists in header indexes '
                . implode(',', array_keys($header))
            );
        }

        return $header[$columnIndex]['type'];
    }

    private function indexValues(array $values, array $rowsHeader, array $columnsHeader): array
    {
        $indexedRows = $this->indexByRowsHeader($values, $rowsHeader);

        return $this->indexByColumnsHeader($indexedRows, $columnsHeader, $this->countDept($rowsHeader));
    }

    private function countDept(array $rowsHeader): int
    {
        $depth = 1; // always at least 1
        $value = current($rowsHeader);
        if (is_array($value)) {
            $depth += $this->countDept($value);
        }

        return $depth;
    }

    private function indexByRowsHeader(array $toIndex, array $rowKeys): array
    {
        if (count($rowKeys) === 0) {
            return $toIndex;
        }
        $indexed = [];
        foreach ($rowKeys as $keyPart => $keyPartsOrRowIndex) {
            if (is_int($keyPartsOrRowIndex)) { // last key pointing to row index
                $indexed[$keyPart] = $toIndex[$keyPartsOrRowIndex];
            } else {
                $indexed[$keyPart] = $this->indexByRowsHeader($toIndex, $keyPartsOrRowIndex);
            }
        }

        return $indexed;
    }

    private function indexByColumnsHeader(array $toIndex, array $columnKeys, $stepsToBottom): array
    {
        $indexed = [];
        foreach ($toIndex as $rowKeyOrColumnIndex => $rowOrFinalValue) {
            if (!is_array($rowOrFinalValue) || $stepsToBottom === 0 /* we are at bottom already */) {
                $columnKey = $columnKeys[$rowKeyOrColumnIndex];
                $indexed[$columnKey] = $rowOrFinalValue;
            } else {
                $indexed[$rowKeyOrColumnIndex] = $this->indexByColumnsHeader($rowOrFinalValue, $columnKeys, $stepsToBottom - 1);
            }
        }

        return $indexed;
    }
}