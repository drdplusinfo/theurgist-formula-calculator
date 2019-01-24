<?php
declare(strict_types=1);

namespace DrdPlus\CalculatorSkeleton;

use Granam\Strict\Object\StrictObject;

class CurrentValues extends StrictObject
{
    /** @var array */
    private $selectedValues;
    /** @var History */
    private $memory;

    /**
     * @param array $selectedValues
     * @param Memory $memory
     */
    public function __construct(array $selectedValues, Memory $memory)
    {
        $this->selectedValues = $selectedValues;
        $this->memory = $memory;
    }

    /**
     * @param string $name
     * @return string|string[]|null
     */
    public function getCurrentValue(string $name)
    {
        if (\array_key_exists($name, $this->selectedValues)) {
            return $this->selectedValues[$name];
        }

        return $this->memory->getValue($name);
    }

    /**
     * @param string $name
     * @return null|string[]|array|string
     */
    public function getSelectedValue(string $name)
    {
        return $this->selectedValues[$name] ?? null;
    }
}