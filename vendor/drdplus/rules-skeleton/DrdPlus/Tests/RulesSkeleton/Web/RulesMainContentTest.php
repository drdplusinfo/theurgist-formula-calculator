<?php declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton\Web;

use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\RulesSkeleton\Request;
use DrdPlus\Tests\RulesSkeleton\TestsConfiguration;
use Gt\Dom\Element;
use Gt\Dom\Node;

class RulesMainContentTest extends MainContentTest
{
    /**
     * @test
     */
    public function Every_plus_after_2d6_is_upper_indexed(): void
    {
        self::assertSame(
            0,
            \preg_match(
                '~.{0,10}2k6\s*(?!<span class="upper-index">\+</span>).{0,20}\+~',
                $this->getContentWithoutIds(),
                $matches
            ),
            \var_export($matches, true)
        );
    }

    private function getContentWithoutIds(): string
    {
        $document = clone $this->getHtmlDocument();
        /** @var Element $body */
        $body = $document->getElementsByTagName('body')[0];
        $this->removeIds($body);

        return $document->saveHTML();
    }

    private function removeIds(Element $element): void
    {
        if ($element->hasAttribute('id')) {
            $element->removeAttribute('id');
        }
        foreach ($element->children as $child) {
            $this->removeIds($child);
        }
    }

    /**
     * @test
     */
    public function Every_registered_trademark_and_trademark_symbols_are_upper_indexed(): void
    {
        self::assertSame(
            0,
            \preg_match(
                '~.{0,10}(?:(?<!<span class="upper-index">)\s*[®™]|[®™]\s*(?!</span>).{0,10})~u',
                $this->getContent(),
                $matches
            ),
            \var_export($matches, true)
        );
    }

    /**
     * @test
     */
    public function Every_id_is_unique(): void
    {
        $ids = $this->parseAllIds($this->getHtmlDocument());
        $idsCount = \array_count_values($ids);
        $duplicatedIds = \array_filter(
            $idsCount,
            static function (int $count) {
                return $count > 1;
            }
        );
        self::assertSame([], $duplicatedIds, 'Some IDs are used multiple times');
    }

    /**
     * @test
     */
    public function I_can_navigate_to_every_heading_by_expected_anchor(): void
    {
        $htmlDocument = $this->getHtmlDocument();
        $allHeadings = [];
        for ($tagLevel = 1; $tagLevel <= 6; $tagLevel++) {
            $headings = $htmlDocument->getElementsByTagName('h' . $tagLevel);
            foreach ($headings as $heading) {
                $allHeadings[] = $heading;
                $id = $heading->id;
                if ($heading->prop_get_classList()->contains(HtmlHelper::CLASS_HEADING_WITHOUT_ID)) {
                    self::assertEmpty(
                        $id,
                        sprintf(
                            "No ID expected for '%s' as is marked by class '%s'",
                            $heading->outerHTML,
                            HtmlHelper::CLASS_HEADING_WITHOUT_ID
                        )
                    );
                    continue;
                }
                self::assertNotEmpty($id, 'Expected some ID for ' . $heading->outerHTML);
                $anchors = $heading->getElementsByTagName('a');
                self::assertCount(1, $anchors, 'Expected single anchor in ' . $heading->outerHTML);
                $anchor = $anchors->current();
                $href = $anchor->getAttribute('href');
                self::assertNotEmpty($href, 'Expected some href of anchor in ' . $heading->outerHTML);
                self::assertSame('#' . $id, $href, 'Expected anchor pointing to the heading ID');
                $headingText = '';
                foreach ($anchor->childNodes as $childNode) {
                    /** @var Node $childNode */
                    if ($childNode->nodeType === \XML_TEXT_NODE) {
                        $headingText = $childNode->textContent;
                        break;
                    }
                }
                self::assertNotEmpty($headingText, 'Expected some human name for heading ' . $heading->outerHTML);
                $idFromText = HtmlHelper::toId($headingText);
                self::assertSame($id, $idFromText, "Expected different ID as created from '$headingText' heading");
            }
        }
        if (!$this->getTestsConfiguration()->hasHeadings()) {
            self::assertCount(
                0,
                $allHeadings,
                sprintf("No headings expected as test configuration says by '%s'", TestsConfiguration::HAS_HEADINGS)
            );
        } else {
            self::assertNotEmpty($allHeadings, 'Expected some headings');
        }
    }

    /**
     * @test
     */
    public function Authors_got_heading(): void
    {
        $authorsHeading = $this->getHtmlDocument()->getElementById(HtmlHelper::ID_AUTHORS);
        if (!$this->getTestsConfiguration()->hasAuthors()) {
            self::assertEmpty(
                $authorsHeading,
                sprintf("Authors are not expected as test configuration says by '%s'", TestsConfiguration::HAS_AUTHORS)
            );

            return;
        }
        self::assertNotEmpty(
            $authorsHeading,
            sprintf("Authors should have h3 heading as test configuration says by '%s'", TestsConfiguration::HAS_AUTHORS)
        );
        self::assertSame(
            'h3',
            $authorsHeading->nodeName,
            'Authors heading should be h3, but is ' . $authorsHeading->nodeName
        );
    }

    /**
     * @test
     */
    public function Authors_are_mentioned(): void
    {
        $body = $this->getHtmlDocument()->body;
        $rulesAuthors = $body->getElementsByClassName(HtmlHelper::CLASS_RULES_AUTHORS);
        if (!$this->getTestsConfiguration()->hasAuthors()) {
            self::assertCount(
                0,
                $rulesAuthors,
                sprintf("No rules authors expected as test configuration says by '%s'", TestsConfiguration::HAS_AUTHORS)
            );

            return;
        }
        self::assertCount(
            1,
            $rulesAuthors,
            sprintf(
                "Expected one '%s' HTML class in rules content as test configuration says by '%s', got %d of them",
                HtmlHelper::CLASS_RULES_AUTHORS,
                TestsConfiguration::HAS_AUTHORS,
                $rulesAuthors->count()
            )
        );
        $rulesAuthors = $rulesAuthors->current();
        self::assertNotEmpty(\trim($rulesAuthors->textContent), 'Expected some content of rules authors');
    }

    /**
     * @test
     */
    public function I_can_get_routed_content()
    {
        if (!$this->isSkeletonChecked()) {
            self::assertTrue(true);
            return;
        }
        $routedContent = $this->getHtmlDocument([], [], [], '/routed')->getElementById('just_some_element_from_routed_content');
        self::assertNotEmpty($routedContent, 'Expected content not found on test route /routed');

        $hyphenRoutedContent = $this->getHtmlDocument([], [], [], '/draci-a-dracata')->getElementById('just_some_element_from_draci_a_dracata');
        self::assertNotEmpty($hyphenRoutedContent, 'Expected content not found on test route /draci-a-dracata');
    }

    /**
     * @test
     */
    public function I_will_get_pretty_not_found_page_on_unknown_route()
    {
        $nonExistingRoute = $this->getTestsConfiguration()->getLocalUrl() . '/' . uniqid('non-existing-route-', true);
        $this->passIn();
        $response = $this->fetchContentFromUrl($nonExistingRoute . '?' . Request::TRIAL . '=1', true);
        $this->passOut();
        self::assertSame(404, $response['responseHttpCode']);
        self::assertStringContainsStringIgnoringCase('kde nic tu nic', $response['content']);
    }

    /**
     * @test
     */
    public function Content_is_styled()
    {
        $contents = $this->getHtmlDocument()->getElementsByClassName(HtmlHelper::CLASS_CONTENT);
        if ($this->isRulesSkeletonChecked() || $this->getTestsConfiguration()->hasMarkedContent()) {
            self::assertNotEmpty(
                $contents,
                sprintf("Expected some content marked by '%s' class as tests configuration says by '%s'", HtmlHelper::CLASS_CONTENT, TestsConfiguration::HAS_MARKED_CONTENT)
            );
        } else {
            self::assertCount(
                0,
                $contents,
                sprintf("No content marked by '%s' class expected as tests configuration says by '%s'", HtmlHelper::CLASS_CONTENT, TestsConfiguration::HAS_MARKED_CONTENT)
            );
        }
    }
}