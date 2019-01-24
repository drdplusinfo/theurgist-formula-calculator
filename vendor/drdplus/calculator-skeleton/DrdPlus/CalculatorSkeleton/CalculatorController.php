<?php
declare(strict_types=1);

namespace DrdPlus\CalculatorSkeleton;

use DrdPlus\RulesSkeleton\RulesController;
use DrdPlus\RulesSkeleton\Web\RulesContent;

class CalculatorController extends RulesController
{
    /** @var CalculatorServicesContainer */
    private $calculatorServicesContainer;

    /**
     * @param CalculatorServicesContainer $calculationServicesContainer
     * @throws \DrdPlus\CalculatorSkeleton\Exceptions\CookiesPostfixIsMissing
     */
    public function __construct(CalculatorServicesContainer $calculationServicesContainer)
    {
        parent::__construct($calculationServicesContainer);
        $this->calculatorServicesContainer = $calculationServicesContainer;
    }

    /**
     * @return RulesContent|CalculatorContent
     */
    public function getRulesContent(): RulesContent
    {
        return $this->calculatorServicesContainer->getCalculatorContent();
    }

    /**
     * Its almost same as @see getBagEnds, but gives in a flat array BOTH array values AND indexes from given array
     *
     * @param array $values
     * @return array
     */
    protected function toFlatArray(array $values): array
    {
        $flat = [];
        foreach ($values as $index => $value) {
            if (\is_array($value)) {
                $flat[] = $index;
                foreach ($this->toFlatArray($value) as $subItem) {
                    $flat[] = $subItem;
                }
            } else {
                $flat[] = $value;
            }
        }

        return $flat;
    }

    /**
     * Its almost same as @see toFlatArray, but gives array values only, not indexes
     *
     * @param array $values
     * @return array
     */
    protected function getBagEnds(array $values): array
    {
        $bagEnds = [];
        foreach ($values as $value) {
            if (\is_array($value)) {
                foreach ($this->getBagEnds($value) as $subItem) {
                    $bagEnds[] = $subItem;
                }
            } else {
                $bagEnds[] = $value;
            }
        }

        return $bagEnds;
    }

    /**
     * @param array $additionalParameters
     * @return string
     */
    public function getRequestUrl(array $additionalParameters = []): string
    {
        if (!$additionalParameters) {
            return $_SERVER['REQUEST_URI'] ?? '';
        }
        $values = \array_merge($_GET, $additionalParameters); // values from GET can be overwritten

        return $this->buildUrl($values);
    }

    /**
     * @param array $values
     * @return string
     */
    private function buildUrl(array $values): string
    {
        $query = [];
        foreach ($values as $name => $value) {
            foreach ($this->buildUrlParts($name, $value) as $pair) {
                $query[] = $pair;
            }
        }
        $urlParts = \parse_url($_SERVER['REQUEST_URI'] ?? '');
        $host = '';
        if (!empty($urlParts['scheme'] && !empty($urlParts['host']))) {
            $host = $urlParts['scheme'] . '://' . $urlParts['host'];
        }
        $queryString = '';
        if ($query) {
            $queryString = '/?' . \implode('&', $query);
        }

        return $host . $queryString;
    }

    /**
     * @param string $name
     * @param array|string $value
     * @return array|string[]
     */
    private function buildUrlParts(string $name, $value): array
    {
        if (!\is_array($value)) {
            return [\urlencode($name) . '=' . \urlencode($value)];
        }
        $pairs = [];
        foreach ((array)$value as $part) {
            foreach ($this->buildUrlParts($name . '[]', $part) as $pair) {
                $pairs[] = $pair;
            }
        }

        return $pairs;
    }

    /**
     * @param array $except
     * @return string
     */
    public function getCurrentValuesAsHiddenInputs(array $except = []): string
    {
        $html = [];
        foreach ($this->getSelectedValues() as $name => $value) {
            if (\in_array($name, $except, true)) {
                continue;
            }
            if (!\is_array($value)) {
                $html[] = "<input type='hidden' name='" . \htmlspecialchars($name) . "' value='" . \htmlspecialchars($value) . "'>";
            } else {
                foreach ($value as $item) {
                    $html[] = "<input type='hidden' name='" . \htmlspecialchars($name) . "[]' value='" . \htmlspecialchars($item) . "'>";
                }
            }
        }

        return \implode("\n", $html);
    }

    /**
     * @return array
     */
    public function getSelectedValues(): array
    {
        return $this->calculatorServicesContainer->getMemory()->getIterator()->getArrayCopy();
    }

    /**
     * @param array $exceptParameterNames
     * @return string
     */
    public function getRequestUrlExcept(array $exceptParameterNames): string
    {
        $values = $_GET;
        foreach ($exceptParameterNames as $name) {
            if (\array_key_exists($name, $values)) {
                unset($values[$name]);
            }
        }

        return $this->buildUrl($values);
    }
}