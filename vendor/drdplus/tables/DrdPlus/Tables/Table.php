<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables;

interface Table
{
    /**
     * Values can be in any dept of wrapping arrays, but have to be scalar or to string convertible.
     *
     * @return array|\ArrayAccess|string[][]|string[][][]
     */
    public function getIndexedValues(): array;

    /**
     * Same values but in simplified structure of two-level array only.
     *
     * @return array|\ArrayAccess|string[][]
     */
    public function getValues(): array;

    /**
     * Simplified structure of header names in two-level array.
     *
     * @return array|\ArrayAccess|string[][]
     */
    public function getHeader(): array;

    /**
     * @param int|string|array $singleRowIndexes
     * @param string|int $columnIndex
     * @return bool|int|float|string
     */
    public function getValue($singleRowIndexes, $columnIndex);

}