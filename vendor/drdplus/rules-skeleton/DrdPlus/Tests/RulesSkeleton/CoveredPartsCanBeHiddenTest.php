<?php
namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;
use Gt\Dom\Element;
use Gt\Dom\HTMLDocument;

class CoveredPartsCanBeHiddenTest extends AbstractContentTest
{
    /**
     * @test
     */
    public function I_can_hide_covered_parts(): void
    {
        $html = $this->getRulesContentForDevWithHiddenCovered();
        $document = new HTMLDocument($html);
        $coveredElements = $document->getElementsByClassName('this_contains_covered_only');
        if ($coveredElements->length === 0
            && \strpos($document->head->getElementsByTagName('title')->item(0)->nodeValue, 'skeleton') === false
        ) {
            self::assertFalse(false, 'Nothing to test here');

            return;
        }
        self::assertNotEmpty($coveredElements);
        foreach ($coveredElements as $covered) {
            self::assertTrue($covered->hasChildNodes());
            /** @var Element $childNode */
            foreach ($covered->children as $childNode) {
                self::assertTrue(
                    $childNode->classList->contains('hidden'),
                    'Every covered element should has "hidden" class, this one does not: ' . $childNode->outerHTML
                );
            }
        }
    }
}