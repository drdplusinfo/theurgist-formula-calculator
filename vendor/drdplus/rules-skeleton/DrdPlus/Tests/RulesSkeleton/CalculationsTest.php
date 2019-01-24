<?php
declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;
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
        if (!$tooShortResultNames && !$this->isSkeletonChecked()) {
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
            if (\count($calculations) === 0 && !$this->isSkeletonChecked()) {
                self::assertFalse(false, 'No calculations in current document');
            } else {
                self::assertNotEmpty($calculations, 'Some calculations expected for skeleton testing');
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
        if (!$tooShortFailureNames && !$tooShortSuccessNames && !$this->isSkeletonChecked()) {
            self::assertFalse(false, 'Nothing to test here');

            return;
        }
        self::assertNotEmpty($tooShortFailureNames, 'Expected some too short failure names for skeleton tests');
        self::assertNotEmpty($tooShortSuccessNames, 'Expected some too short success names for skeleton tests');
        $tooShortFailureNamesRegexp = $this->implodeForRegexpOr($tooShortFailureNames);
        $tooShortSuccessNamesRegexp = $this->implodeForRegexpOr($tooShortSuccessNames);
        $contents = [];
        foreach ($this->getCalculations() as $calculation) {
            foreach ($calculation->getElementsByClassName(HtmlHelper::CLASS_CONTENT) as $contentsFromCalculation) {
                $contents[] = $contentsFromCalculation;
            }
        }
        if (\count($contents) === 0 && !$this->isSkeletonChecked()) {
            self::assertFalse(false, 'No content classes in current document');

            return;
        }
        self::assertNotEmpty($contents, 'Some content class inside calculation class expected');
        foreach ($contents as $content) {
            $parts = \explode('~', $content->textContent ?? '');
            if (\count($parts) < 3) {
                $textContent = \str_replace('&lt;', '<', $content->textContent); // the HTML library may already convert &lt; to <, but we are not sure
                if (\strpos($textContent, '<')) {
                    $parts = [];
                    [$parts[0], $trapAndSameOrGreater] = \explode('<', $textContent ?? '');
                    [$parts[1], $parts[2]] = \explode('≥', $trapAndSameOrGreater);
                }
            }
            $failName = \strtolower(\trim($parts[0] ?? ''));
            self::assertNotRegExp("~^($tooShortFailureNamesRegexp)$~i", $failName, "Expected more specific name of failure for content\n$content->outerHTML");
            $tooShortSuccessNames = \strtolower(\trim($parts[2] ?? ''));
            self::assertNotRegExp("~^($tooShortSuccessNamesRegexp)$~i", $tooShortSuccessNames, "Expected more specific name of success for content\n$content->outerHTML");
        }
    }
}