<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\Theurgist\Spells;

trait ToFlatArrayTrait
{
    /**
     * @param array $items
     * @return array
     */
    protected function toFlatArray(array $items): array
    {
        $flat = [];
        foreach ($items as $item) {
            if (\is_array($item)) {
                foreach ($this->toFlatArray($item) as $subItem) {
                    $flat[] = $subItem;
                }
            } else {
                $flat[] = $item;
            }
        }

        return $flat;
    }
}