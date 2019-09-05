<?php declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;

class StandardModeTest extends AbstractContentTest
{
    /**
     * @test
     */
    public function I_get_notes_styled(): void
    {
        if (!$this->getTestsConfiguration()->hasNotes()) {
            self::assertEmpty(
                $this->getHtmlDocument()->getElementsByClassName(HtmlHelper::CLASS_NOTE),
                sprintf(
                    "No elements with '%s' class expected as test configuration says by '%s'",
                    HtmlHelper::CLASS_NOTE,
                    TestsConfiguration::HAS_NOTES
                )
            );
        } else {
            self::assertNotEmpty(
                $this->getHtmlDocument()->getElementsByClassName(HtmlHelper::CLASS_NOTE),
                sprintf(
                    "Expected at least a single element with '%s' class as test configuration says by '%s'",
                    HtmlHelper::CLASS_NOTE,
                    TestsConfiguration::HAS_NOTES
                )
            );
        }
    }

    /**
     * @test
     */
    public function I_am_not_distracted_by_development_classes(): void
    {
        $htmlDocument = $this->getHtmlDocument();
        self::assertCount(0, $htmlDocument->getElementsByClassName(HtmlHelper::CLASS_COVERED_BY_CODE));
        self::assertCount(0, $htmlDocument->getElementsByClassName(HtmlHelper::CLASS_GENERIC));
        self::assertCount(0, $htmlDocument->getElementsByClassName(HtmlHelper::CLASS_EXCLUDED));
    }
}