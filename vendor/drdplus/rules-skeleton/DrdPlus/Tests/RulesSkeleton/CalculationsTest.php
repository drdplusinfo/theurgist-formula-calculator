<?php declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;
use Granam\String\StringTools;
use Gt\Dom\Element;
use Gt\Dom\HTMLCollection;

class CalculationsTest extends AbstractContentTest
{
    /**
     * @test
     */
    public function Calculation_has_descriptive_name(): void
    {
        $tooShortResultNames = $this->getTestsConfiguration()->getTooShortResultNames();
        if (!$tooShortResultNames) {
            self::assertFalse(false, 'Nothing to test here');

            return;
        }
        self::assertNotEmpty($tooShortResultNames, 'Expected some too short names of results');
        $tooShortResultNamesRegexp = $this->implodeForRegexpOr($tooShortResultNames);
        foreach ($this->getCalculations() as $calculation) {
            $parts = \explode('=', $calculation->textContent ?? '');
            $resultName = \trim($parts[0] ?? '');
            self::assertNotRegExp("~^($tooShortResultNamesRegexp)$~i", $resultName, "Expected more specific name of result in calculation\n$calculation->outerHTML");
        }
    }

    private function implodeForRegexpOr(array $values, string $delimiter = '~'): string
    {
        $regexpParts = [];
        foreach ($values as $value) {
            $regexpParts[] = \preg_quote($value, $delimiter);
        }

        return \implode('|', $regexpParts);
    }

    /**
     * @return HTMLCollection|Element[]
     */
    private function getCalculations(): HTMLCollection
    {
        static $calculations;
        if ($calculations === null) {
            $document = $this->getHtmlDocument();
            $calculations = $document->getElementsByClassName(HtmlHelper::CLASS_CALCULATION);
            if (!$this->isSkeletonChecked() && !$this->getTestsConfiguration()->hasCalculations()) {
                self::assertCount(
                    0,
                    $calculations,
                    sprintf(
                        "No calculations expected in current document as test configuration says by '%s'",
                        TestsConfiguration::HAS_CALCULATIONS
                    )
                );
            } else {
                self::assertNotEmpty(
                    $calculations,
                    sprintf(
                        "Some calculations expected for skeleton testing as test configuration says by '%s'",
                        TestsConfiguration::HAS_CALCULATIONS
                    )
                );
            }
        }

        return $calculations;
    }

    /**
     * @test
     */
    public function Result_content_trap_has_descriptive_name(): void
    {
        $tooShortFailureNames = $this->getTestsConfiguration()->getTooShortFailureNames();
        $tooShortSuccessNames = $this->getTestsConfiguration()->getTooShortSuccessNames();
        if (!$tooShortFailureNames && !$tooShortSuccessNames) {
            self::assertFalse(false, 'Nothing to test here');

            return;
        }
        self::assertNotEmpty($tooShortFailureNames, 'Expected some too short failure names for skeleton tests');
        self::assertNotEmpty($tooShortSuccessNames, 'Expected some too short success names for skeleton tests');
        $tooShortFailureNamesRegexp = $this->implodeForRegexpOr($tooShortFailureNames);
        $tooShortSuccessNamesRegexp = $this->implodeForRegexpOr($tooShortSuccessNames);
        $results = [];
        foreach ($this->getCalculations() as $calculation) {
            foreach ($calculation->getElementsByClassName(HtmlHelper::CLASS_RESULT) as $resultsFromCalculation) {
                $results[] = $resultsFromCalculation;
            }
        }
        if (!$this->getTestsConfiguration()->hasCalculations() || !$this->getTestsConfiguration()->hasMarkedResult()) {
            self::assertCount(
                0,
                $results,
                sprintf(
                    "No result classes in calculations expected as test configuration says by '%s'",
                    !$this->getTestsConfiguration()->hasCalculations()
                        ? TestsConfiguration::HAS_CALCULATIONS
                        : TestsConfiguration::HAS_MARKED_RESULT
                )
            );

            return;
        }
        self::assertNotEmpty(
            $results,
            sprintf("Some result class inside calculation class expected as test cofiguration says by '%s'", TestsConfiguration::HAS_MARKED_RESULT)
        );
        /** @var Element $result */
        foreach ($results as $result) {
            $result = $this->removeClasses($result, [HtmlHelper::CLASS_INVISIBLE_ID, HtmlHelper::CLASS_INVISIBLE, HtmlHelper::CLASS_FORMULA]);
            $textContents = $this->getTextContents($result);
            foreach ($textContents as $textContent) {
                $parts = \explode('~', $textContent);
                if (\count($parts) < 3) {
                    $textContent = \str_replace('&lt;', '<', $textContent); // the HTML library may already convert &lt; to <, but we are not sure
                    if (\strpos($textContent, '<')) {
                        $parts = [];
                        [$parts[0], $trapAndSameOrGreater] = \explode('<', $textContent ?? '');
                        [$parts[1], $parts[2]] = \explode('≥', $trapAndSameOrGreater);
                    }
                }
                $failName = \strtolower(\trim($parts[0] ?? ''));
                self::assertNotRegExp("~^($tooShortFailureNamesRegexp)$~iu", $failName, "Expected more specific name of failure for result\n$result->outerHTML");
                $tooShortSuccessNames = \strtolower(\trim($parts[2] ?? ''));
                self::assertNotRegExp("~^($tooShortSuccessNamesRegexp)$~iu", $tooShortSuccessNames, "Expected more specific name of success for result\n$result->outerHTML");
            }
        }
    }

    private function removeClasses(Element $element, array $classes): Element
    {
        foreach ($classes as $class) {
            foreach ($element->getElementsByClassName($class) as $child) {
                $child->remove();
            }
        }
        return $element;
    }

    private function getTextContents(Element $element): array
    {
        $textContents = [];
        if ($element->tagName === 'table') {
            foreach ($element->getElementsByTagName('tr') as $tableRow) {
                $textContents[] = trim($tableRow->textContent ?? '');
            }
        } else {
            $textContents[] = trim($element->textContent ?? '');
        }

        return $textContents;
    }

    /**
     * @test
     */
    public function I_can_navigate_to_every_calculation_as_it_has_its_id_with_anchor(): void
    {
        if (!$this->isRulesSkeletonChecked() && !$this->getTestsConfiguration()->hasCalculations()) {
            self::assertCount(
                0,
                $this->getCalculations(),
                sprintf("No calculations expected as test configuration says by '%s'", TestsConfiguration::HAS_CALCULATIONS)
            );
            return;
        }
        $allowedCalculationIdPrefixes = $this->getTestsConfiguration()->getAllowedCalculationIdPrefixes();
        $allowedCalculationIdPrefixesRegexp = $this->toRegexpOr($allowedCalculationIdPrefixes);
        $allowedCalculationIdConstantLikePrefixes = \array_map(
            function (string $allowedPrefix) {
                return StringTools::toConstantLikeValue($allowedPrefix);
            },
            $allowedCalculationIdPrefixes
        );
        $allowedCalculationIdConstantLikePrefixesRegexp = $this->toRegexpOr($allowedCalculationIdConstantLikePrefixes);
        foreach ($this->getCalculations() as $calculation) {
            self::assertNotEmpty($calculation->id, 'Missing ID for calculation: ' . \trim($calculation->innerHTML));
            $originalId = $calculation->getAttribute('data-original-id');
            self::assertNotEmpty(
                $originalId,
                sprintf(
                    "Missing data-original-id attribute for calculation of ID '%s', maybe si the ID duplicated?",
                    $calculation->id
                )
            );
            self::assertRegExp("~^($allowedCalculationIdPrefixesRegexp) ~u", $originalId);
            self::assertRegExp("~^($allowedCalculationIdConstantLikePrefixesRegexp)_~u", $calculation->id);
            $anchorToCalculation = null;
            foreach ($calculation->getElementsByTagName('a') as $anchor) {
                if ($anchor->getAttribute('href') === "#{$calculation->id}") {
                    $anchorToCalculation = $anchor;
                    break;
                }
            }
            self::assertNotNull($anchorToCalculation, "No anchor found in calculation with ID '{$originalId}'");
            self::assertNotSame(
                '',
                trim($anchorToCalculation->textContent),
                "No text content found in the anchor if calculation with ID '{$originalId}', therefore the anchor can not be clicked: '{$anchorToCalculation->outerHTML}'"
            );
        }
    }

    private function toRegexpOr(array $values, string $regexpDelimiter = '~'): string
    {
        $escaped = [];
        foreach ($values as $value) {
            $escaped[] = \preg_quote($value, $regexpDelimiter);
        }

        return \implode('|', $escaped);
    }

}