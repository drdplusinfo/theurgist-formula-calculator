<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\Measurements\BaseOfWounds;

use DrdPlus\Calculations\SumAndRound;
use DrdPlus\BaseProperties\Strength;
use DrdPlus\Tables\Table;
use Granam\Integer\IntegerInterface;
use Granam\Integer\Tools\ToInteger;
use Granam\Strict\Object\StrictObject;
use Granam\Tools\ValueDescriber;

/**
 * See PPH page 165 bottom, @link https://pph.drdplus.info/#tabulka_pro_vypocet_zz
 * Technical note: base of wounds is special table, without standard interface.
 */
class BaseOfWoundsTable extends StrictObject implements Table
{

    /** @var array|string[][] */
    private $values;
    /** @var array */
    private $bonuses;
    /** @var array */
    private $axisX;
    /** @var array */
    private $axisY;

    /**
     * @return array|null|\string[][]|\int[][]
     */
    public function getValues(): array
    {
        if ($this->values === null) {
            $this->loadValues();
        }

        return $this->values;
    }

    private function loadValues()
    {
        $this->values = $this->fetchData();
    }

    /**
     * @return array|string[][]
     */
    private function fetchData(): array
    {
        $data = [];
        $handle = fopen(__DIR__ . '/data/base_of_wounds.csv', 'rb');
        while ($row = fgetcsv($handle)) {
            $data[] = array_map(
                function ($value) {
                    $number = str_replace('−' /* ASCII 226 */, '-' /* ASCII 45 */, $value);

                    return is_numeric($number)
                        ? (int)$number
                        : $number; // the ⊕ sign
                },
                $row
            );
        }

        return $data;
    }

    /**
     * @param array|int[][] $data
     * @return array|int[]
     */
    private function collectAxisY(array $data): array
    {
        $axisY = [];
        foreach ($data as $index => $row) {
            $axisY[$index] = $row[0]; // value from first column
        }
        unset ($axisY[0]); // removing blank first value ("⊕")

        return $this->transpose($axisY); // note: rank (index) starts by number 1
    }

    /**
     * @return array|null|\string[][]
     */
    public function getIndexedValues(): array
    {
        return $this->getValues();
    }

    /**
     * @return array
     */
    public function getHeader(): array
    {
        return [];
    }

    /**
     * Usable for sum of values, represented by bonuses, like distances 0 and 0 (1 meter) = 6 (2 meters).
     * See note on PPH page 164, bottom, @link https://pph.drdplus.info/#soucet_bonusu
     * Warning - the result depends on the SEQUENCE of given bonuses.
     *
     * @param array|int|IntegerInterface[] $bonuses
     * @return int
     * @throws \DrdPlus\Tables\Measurements\BaseOfWounds\Exceptions\SumOfBonusesResultsIntoNull
     * @throws \DrdPlus\Tables\Measurements\BaseOfWounds\Exceptions\NoColumnExistsOnProvidedIndex
     * @throws \DrdPlus\Tables\Measurements\BaseOfWounds\Exceptions\NoRowExistsOnProvidedIndex
     */
    public function sumValuesViaBonuses(array $bonuses): int
    {
        while (($firstBonus = array_shift($bonuses)) !== null && ($secondBonus = array_shift($bonuses)) !== null) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $firstBonus = ToInteger::toInteger($firstBonus);
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $secondBonus = ToInteger::toInteger($secondBonus);
            $intersection = $this->getBonusesIntersection([$firstBonus, $secondBonus]);
            /** see note on PPH page 164, bottom, @link https://pph.drdplus.info/#soucet_bonusu */
            $sum = $intersection + 5;
            if (count($bonuses) === 0) {
                return $sum;
            }
            array_unshift($bonuses, $sum);
        }

        if ($firstBonus !== null) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return ToInteger::toInteger($firstBonus); // the first if single bonus
        }

        throw new Exceptions\SumOfBonusesResultsIntoNull('Sum of ' . count($bonuses) . ' bonuses resulted into NULL');
    }

    /**
     * Warning - the result of more thant two bonuses depends on the SEQUENCE of given bonuses
     * (sequence of pairs respectively).
     *
     * @param array|int[]|IntegerInterface[] $bonuses
     * @return int summarized bonuses
     * @throws \DrdPlus\Tables\Measurements\BaseOfWounds\Exceptions\SumOfBonusesResultsIntoNull
     * @throws \DrdPlus\Tables\Measurements\BaseOfWounds\Exceptions\NoColumnExistsOnProvidedIndex
     * @throws \DrdPlus\Tables\Measurements\BaseOfWounds\Exceptions\NoRowExistsOnProvidedIndex
     */
    public function getBonusesIntersection(array $bonuses): int
    {
        while (($firstBonus = array_shift($bonuses)) !== null && ($secondBonus = array_shift($bonuses)) !== null) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $bonusesSum = $this->getBonusesSum(ToInteger::toInteger($firstBonus), ToInteger::toInteger($secondBonus));
            if (count($bonuses) === 0) { // noting more to count
                return $bonusesSum;
            }
            // warning - the result is dependent on the sequence of bonuses if more than two
            array_unshift($bonuses, $bonusesSum); // add the sum to the beginning and run another sum-iteration
        }
        if ($firstBonus !== null) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return ToInteger::toInteger($firstBonus); // the first if single bonus
        }

        throw new Exceptions\SumOfBonusesResultsIntoNull('Sum of ' . count($bonuses) . ' bonuses resulted into NULL');
    }

    /**
     * @param int|IntegerInterface $firstBonus
     * @param int|IntegerInterface $secondBonus
     * @return int
     * @throws \DrdPlus\Tables\Measurements\BaseOfWounds\Exceptions\NoColumnExistsOnProvidedIndex
     * @throws \DrdPlus\Tables\Measurements\BaseOfWounds\Exceptions\NoRowExistsOnProvidedIndex
     */
    private function getBonusesSum($firstBonus, $secondBonus): int
    {
        $columnRank = $this->getColumnRank($firstBonus);
        if ($columnRank === null) {
            return $this->calculateBaseOfWoundsValue($firstBonus, $secondBonus);
        }
        $rowRank = $this->getRowRank($secondBonus);
        if ($rowRank === null) {
            return $this->calculateBaseOfWoundsValue($firstBonus, $secondBonus);
        }

        return $this->getBonuses()[$rowRank][$columnRank];
    }

    private function calculateBaseOfWoundsValue(int $strength, int $weaponBaseOfWounds): int
    {
        $strengthAsValue = $this->calculateValueFromBonus($strength);
        $weaponBaseOfWoundsAsValue = $this->calculateValueFromBonus($weaponBaseOfWounds);
        $sumAsBonus = $this->calculateBonus($strengthAsValue + $weaponBaseOfWoundsAsValue);

        /** @link https://pph.drdplus.info/#vypocet_zakladu_zraneni */
        return $sumAsBonus - 5;
    }

    /**
     * @param int $bonus
     * @return int|null
     * @throws \DrdPlus\Tables\Measurements\BaseOfWounds\Exceptions\NoColumnExistsOnProvidedIndex
     */
    private function getColumnRank($bonus):? int
    {
        if (array_key_exists($bonus, $this->getAxisX())) {
            return $this->getAxisX()[$bonus];
        }

        return null;
    }

    /**
     * @return array|int[]
     */
    private function getAxisX(): array
    {
        if ($this->axisX === null) {
            $this->axisX = $this->collectAxisX($this->getValues());
        }

        return $this->axisX;
    }

    /**
     * @param array|int[][] $data
     * @return array|int[][]
     */
    private function collectAxisX(array $data): array
    {
        $axisX = $data[0]; // first row
        unset($axisX[0]); // removing blank first value ("⊕")

        return $this->transpose($axisX); // note: rank (index) starts by number 1
    }

    /**
     * @param array $data
     * @return array
     */
    private function transpose(array $data): array
    {
        return array_flip($data);
    }

    /**
     * @param int $bonus
     * @return int|null
     * @throws \DrdPlus\Tables\Measurements\BaseOfWounds\Exceptions\NoRowExistsOnProvidedIndex
     */
    private function getRowRank(int $bonus):? int
    {
        if (array_key_exists($bonus, $this->getAxisY())) {
            return $this->getAxisY()[$bonus];
        }

        return null;
    }

    /**
     * @return array|int[]
     */
    private function getAxisY(): array
    {
        if ($this->axisY === null) {
            $this->axisY = $this->collectAxisY($this->values);
        }

        return $this->axisY;
    }

    /**
     * @return array
     */
    private function getBonuses(): array
    {
        if ($this->bonuses === null) {
            $this->bonuses = $this->collectBonuses($this->values);
        }

        return $this->bonuses;
    }

    /**
     * @param array|int[][] $data
     * @return array|int[][]
     */
    private function collectBonuses(array $data): array
    {
        unset($data[0]); // removing first row - the axis X header
        $rankedBonuses = [];
        foreach ($data as $rowRank => $row) {
            unset($row[0]); // removing first column - the axis Y header
            $rankedBonuses[$rowRank] = $row;
        }

        return $rankedBonuses; // indexed as row index => column index => bonus
    }

    /**
     * @param int|IntegerInterface $singleRowIndex
     * @param int|IntegerInterface $columnIndex
     * @return int
     * @throws \DrdPlus\Tables\Measurements\BaseOfWounds\Exceptions\NoRowExistsOnProvidedIndex
     * @throws \DrdPlus\Tables\Measurements\BaseOfWounds\Exceptions\NoColumnExistsOnProvidedIndex
     */
    public function getValue($singleRowIndex, $columnIndex): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $integerRowIndex = ToInteger::toInteger($singleRowIndex);
        $values = $this->getValues();
        if (!array_key_exists($integerRowIndex, $values)) {
            throw new Exceptions\NoRowExistsOnProvidedIndex(
                'No row exists for given row index ' . ValueDescriber::describe($singleRowIndex)
            );
        }
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $integerColumnIndex = ToInteger::toInteger($columnIndex);
        if (!array_key_exists($integerColumnIndex, $values[$integerRowIndex])) {
            throw new Exceptions\NoColumnExistsOnProvidedIndex(
                'No column exists for given column index ' . ValueDescriber::describe($columnIndex)
            );
        }

        return $values[$integerRowIndex][$integerColumnIndex];
    }

    /**
     * @param Strength $strength
     * @param IntegerInterface $weaponBaseOfWounds
     * @return int
     * @throws \DrdPlus\Tables\Measurements\BaseOfWounds\Exceptions\NoColumnExistsOnProvidedIndex
     * @throws \DrdPlus\Tables\Measurements\BaseOfWounds\Exceptions\NoRowExistsOnProvidedIndex
     */
    public function getBaseOfWounds(Strength $strength, IntegerInterface $weaponBaseOfWounds): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getBonusesIntersection([$strength, $weaponBaseOfWounds]);
    }

    /**
     * @param int $bonus
     * @return float
     */
    private function calculateValueFromBonus(int $bonus): float
    {
        return 10 ** ($bonus / 20 + 0.5);  // intentionally no rounding
    }

    /**
     * @param float $value
     * @return int
     */
    private function calculateBonus(float $value): int
    {
        /**
         * Because doubled bonus = bonus + 6 and ten-multiply bonus = bonus + 20
         *
         * @link https://pph.drdplus.info/#scitani_a_odcitani
         */
        return SumAndRound::round(20 * log10($value) - 10);
    }

    /**
     * @param int|IntegerInterface $bonus
     * @return int
     */
    public function halfBonus($bonus): int
    {
        // see PPH page 72, left column
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return ToInteger::toInteger($bonus) - 6;
    }

    /**
     * @param int|IntegerInterface $bonus
     * @return int
     */
    public function doubleBonus($bonus): int
    {
        // see PPH page 72, left column
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return ToInteger::toInteger($bonus) + 6;
    }

    /**
     * @param int|IntegerInterface $bonus
     * @return int
     */
    public function tenMultipleBonus($bonus): int
    {
        // see PPH page 72, left column
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return ToInteger::toInteger($bonus) + 20;
    }

    /**
     * @param int|IntegerInterface $bonus
     * @return int
     */
    public function tenMinifyBonus($bonus): int
    {
        // see PPH page 72, left column
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return ToInteger::toInteger($bonus) - 20;
    }

}