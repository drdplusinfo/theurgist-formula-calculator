<?php
declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;
use Granam\String\StringTools;
use Gt\Dom\Element;
use Gt\Dom\HTMLDocument;

class AnchorsTest extends AbstractContentTest
{

    private const ID_WITH_ALLOWED_ELEMENTS_ONLY = 'with_allowed_elements_only';

    /** @var HTMLDocument[]|array */
    private static $externalHtmlDocuments;

    /**
     * @test
     */
    public function Local_anchors_with_hashes_point_to_existing_ids(): void
    {
        $html = $this->getHtmlDocument();
        $localAnchors = $this->getLocalAnchors();
        if (!$this->isSkeletonChecked() && !$this->getTestsConfiguration()->hasLocalLinks()) {
            self::assertCount(
                0,
                $localAnchors,
                'No local anchors expected as tests config says there are no IDs to make anchors from: '
                . "\n" . \implode("\n", \array_map(function (Element $anchor) {
                    return $anchor->getAttribute('href');
                }, $localAnchors))
            );

            return;
        }
        self::assertNotEmpty($localAnchors, 'Some local anchors expected');
        foreach ($this->getLocalAnchors() as $localAnchor) {
            $expectedId = \substr($localAnchor->getAttribute('href'), 1); // just remove leading #
            /** @var Element $target */
            $target = $html->getElementById($expectedId);
            self::assertNotEmpty($target, 'No element found by ID ' . $expectedId);
            foreach ($this->classesAllowingInnerLinksTobeHidden() as $classAllowingInnerLinksTobeHidden) {
                if ($target->classList->contains($classAllowingInnerLinksTobeHidden)) {
                    return;
                }
            }
            self::assertNotContains('hidden', (string)$target->className, "Inner link of ID $expectedId should not be hidden");
            self::assertNotRegExp('~(display:\s*none|visibility:\s*hidden)~', (string)$target->getAttribute('style'));
        }
    }

    private function classesAllowingInnerLinksTobeHidden(): array
    {
        return [];
    }

    /**
     * @return array|Element[]
     */
    private function getLocalAnchors(): array
    {
        $html = $this->getHtmlDocument();
        $localAnchors = [];
        /** @var Element $anchor */
        foreach ($html->getElementsByTagName('a') as $anchor) {
            if (\strpos($anchor->getAttribute('href'), '#') === 0) {
                $localAnchors[] = $anchor;
            }
        }

        return $localAnchors;
    }

    private static $checkedExternalAnchors = [];

    /**
     * @test
     */
    public function All_external_anchors_can_be_reached(): void
    {
        $skippedExternalUrls = [];
        foreach ($this->getExternalAnchors() as $originalLink) {
            $link = HtmlHelper::turnToLocalLink($originalLink);
            if (\in_array($link, self::$checkedExternalAnchors, true)) {
                continue;
            }
            $weAreOffline = $this->isLinkAccessible($originalLink, $link);
            if ($weAreOffline) {
                $skippedExternalUrls[] = $link;
            } else {
                $responseHttpCode = false;
                $redirectUrl = '';
                $error = '';
                $isDrdPlus = $this->isDrdPlusLink($link);
                $tempFileName = 'external_anchor_response_code_' . \md5($link) . '.tmp';
                if (!$isDrdPlus) {
                    $responseHttpCode = (int)$this->getFromCache($tempFileName);
                }
                if (!$responseHttpCode) {
                    [
                        'responseHttpCode' => $responseHttpCode,
                        'redirectUrl' => $redirectUrl,
                        'error' => $error,
                    ] = $this->fetchContentFromLink($link, false /* just headers*/);
                    if (!$isDrdPlus && $responseHttpCode >= 200 && $responseHttpCode < 300) {
                        $this->cacheContent((string)$responseHttpCode, $tempFileName, $isDrdPlus, $responseHttpCode);
                    }
                }
                self::assertTrue(
                    $responseHttpCode >= 200 && $responseHttpCode < 300,
                    "Could not reach $link, got response code $responseHttpCode and redirect URL '$redirectUrl' ($error)"
                );
            }
            self::$checkedExternalAnchors[] = $link;
        }
        if ($skippedExternalUrls) {
            self::markTestSkipped(
                'Some external URLs have been skipped as we are probably offline: ' .
                \print_r($skippedExternalUrls, true)
            );
        }
    }

    private function isDrdPlusLink(string $link): bool
    {
        return \strpos($link, 'drdplus.loc') !== false || \strpos($link, 'drdplus.info') !== false;
    }

    private function isLinkAccessible(string $originalLink, string $localizedLink): bool
    {
        if ($originalLink !== $localizedLink) {
            return false; // nothing changed so it is not an drdplus.info link and is still external
        }
        $host = \parse_url($localizedLink, \PHP_URL_HOST);

        return $host !== false
            && !\filter_var($host, \FILTER_VALIDATE_IP)
            && \gethostbyname($host) === $host; // instead of IP address we got again the site name
    }

    private function getFromCache(string $cacheFileBaseName): string
    {
        $tempFile = \sys_get_temp_dir() . '/' . $cacheFileBaseName;
        if (!\file_exists($tempFile)) {
            return '';
        }
        if (\filemtime($tempFile) > (\time() - 3600)) {
            return \file_get_contents($tempFile);
        }
        \unlink($tempFile);

        return '';
    }

    private function cacheContent(string $content, string $cacheFileBaseName, bool $isDrdPlus, int $responseCode): bool
    {
        if ($isDrdPlus || $responseCode < 200 || $responseCode >= 300) {
            return false;
        }
        self::assertNotSame('', $content, 'Given content to cache is empty');
        $tempDir = \sys_get_temp_dir() . '/frontend-skeleton';
        self::assertTrue(
            \file_exists($tempDir) || \mkdir($tempDir, 0775) || \is_dir($tempDir),
            "Can not create dir for cached test content $tempDir"
        );
        $tempFile = $tempDir . '/' . $cacheFileBaseName;
        self::assertNotEmpty(\file_put_contents($tempFile, $content), "Nothing has been saved to $tempFile");

        return true;
    }

    /**
     * @test
     */
    public function External_anchors_with_hashes_point_to_existing_ids(): void
    {
        $externalAnchorsWithHash = $this->getExternalAnchorsWithHash();
        if (!$this->isSkeletonChecked() && !$this->getTestsConfiguration()->hasExternalAnchorsWithHashes()) {
            self::assertCount(
                0,
                $externalAnchorsWithHash,
                'No external anchors expected according to tests config'
            );

            return;
        }
        self::assertNotEmpty($externalAnchorsWithHash, 'Some external anchors expected');
        $skippedExternalUrls = [];
        foreach ($externalAnchorsWithHash as $originalLink) {
            $link = HtmlHelper::turnToLocalLink($originalLink);
            if ($this->isLinkAccessible($originalLink, $link)) {
                $skippedExternalUrls[] = $link;
                continue;
            }
            $html = $this->getExternalHtmlDocument($link);
            $expectedId = \substr($link, \strpos($link, '#') + 1); // just remove leading #
            /** @var Element $target */
            $target = $html->getElementById($expectedId);
            self::assertNotEmpty(
                $target,
                'No element found by ID ' . $expectedId . ' in a document with URL ' . $link
                . ($link !== $originalLink ? ' (originally ' . $originalLink . ')' : '')
            );
            self::assertNotRegExp('~(display:\s*none|visibility:\s*hidden)~', (string)$target->getAttribute('style'));
        }
        if ($skippedExternalUrls) {
            self::markTestSkipped(
                'Some external URLs have been skipped as we are probably offline: ' .
                \print_r($skippedExternalUrls, true)
            );
        }
    }

    /**
     * @return array|string[]
     */
    private function getExternalAnchorsWithHash(): array
    {
        $externalAnchorsWithHash = [];
        foreach ($this->getExternalAnchors() as $anchor) {
            if (\strpos($anchor, '#') > 0) {
                $externalAnchorsWithHash[] = $anchor;
            }
        }

        return $externalAnchorsWithHash;
    }

    private function getExternalHtmlDocument(string $href): HTMLDocument
    {
        $link = \substr($href, 0, \strpos($href, '#') ?: null);
        if ((self::$externalHtmlDocuments[$link] ?? null) === null) {
            $isDrdPlus = false;
            if ($this->isDrdPlusLink($link)) {
                self::assertNotEmpty(
                    \preg_match('~//(?<subDomain>[^.]+([.][^.]+)*)\.drdplus\.~', $link),
                    "Expected some sub-domain in link $link"
                );
                $isDrdPlus = true;
            }
            $content = '';
            $tempFileName = 'external_anchor_content_' . \md5($link) . '.tmp';
            if (!$isDrdPlus) {
                $content = $this->getFromCache($tempFileName);
            }
            if (!$content) {
                [
                    'responseHttpCode' => $responseHttpCode,
                    'redirectUrl' => $redirectUrl,
                    'error' => $error,
                    'content' => $content,
                ] = $this->fetchContentFromLink($link, true /* fetch body */, $this->getPostDataToFetchContent($isDrdPlus));
                self::assertTrue(
                    $responseHttpCode >= 200 && $responseHttpCode < 300,
                    "Could not reach $link, got response code $responseHttpCode and redirect URL '$redirectUrl' ($error)"
                );
                self::assertNotEmpty($content, 'Nothing has been fetched from URL ' . $link);
                $this->cacheContent($content, $tempFileName, $isDrdPlus, $responseHttpCode);
            }
            self::$externalHtmlDocuments[$link] = @new HTMLDocument($content);
            if ($isDrdPlus) {
                self::assertCount(
                    0,
                    self::$externalHtmlDocuments[$link]->getElementsByTagName('form'),
                    'Seems we have not passed ownership check for ' . $href
                );
            }
        }

        return self::$externalHtmlDocuments[$link];
    }

    /**
     * @test
     */
    public function Anchor_to_ID_self_is_not_created_if_contains_anchor_element(): void
    {
        $document = $this->getHtmlDocument();
        $noAnchorsForMe = $document->getElementById(StringTools::toConstantLikeValue('no-anchor-for-me'));
        if (!$noAnchorsForMe && !$this->isSkeletonChecked()) {
            self::assertFalse(false, 'Nothing to test here');

            return;
        }
        self::assertNotEmpty($noAnchorsForMe, "Missing testing element with ID 'no-anchor-for-me'");
        $links = $noAnchorsForMe->getElementsByTagName('a');
        self::assertNotEmpty($links);
        /** @var \DOMElement $noAnchorsForMe */
        $idLink = '#' . $noAnchorsForMe->getAttribute('id');
        /** @var \DOMElement $link */
        foreach ($links as $link) {
            self::assertNotSame($idLink, $link->getAttribute('href'), "No anchor pointing to ID self expected: $idLink");
        }
    }

    /**
     * @test
     */
    public function Original_ids_do_not_have_links_to_self(): void
    {
        $document = $this->getHtmlDocument();
        $originalIds = $document->getElementsByClassName(HtmlHelper::CLASS_INVISIBLE_ID);
        if (!$this->isSkeletonChecked() && !$this->getTestsConfiguration()->hasIds()) {
            self::assertCount(
                0,
                $originalIds,
                'No original IDs, identified by CSS class ' . HtmlHelper::CLASS_INVISIBLE_ID . ' expected, got '
                . \implode("\n", \array_map(function (Element $element) {
                    return $element->outerHTML;
                }, $this->collectionToArray($originalIds)))
            );

            return;
        }
        self::assertNotEmpty(
            $originalIds,
            sprintf(
                'Expected some IDs identified by a HTML class %s according to test configuration %s',
                HtmlHelper::CLASS_INVISIBLE,
                TestsConfiguration::HAS_IDS
            )
        );
        foreach ($originalIds as $originalId) {
            self::assertSame('', $originalId->innerHTML);
        }
    }

    private function collectionToArray(\Iterator $collection): array
    {
        $array = [];
        foreach ($collection as $item) {
            $array[] = $item;
        }

        return $array;
    }

    /**
     * @test
     */
    public function Only_allowed_elements_are_moved_into_injected_link(): void
    {
        $document = $this->getHtmlDocument();
        $withAllowedElementsOnly = $document->getElementById(self::ID_WITH_ALLOWED_ELEMENTS_ONLY);
        if (!$withAllowedElementsOnly && !$this->isSkeletonChecked()) {
            self::assertFalse(false, 'Nothing to test here');

            return;
        }
        self::assertNotEmpty(
            $withAllowedElementsOnly,
            'Missing testing HTML element with ID ' . self::ID_WITH_ALLOWED_ELEMENTS_ONLY
        );
        $anchors = $withAllowedElementsOnly->getElementsByTagName('a');
        self::assertCount(1, $anchors);
        $anchor = $anchors->item(0);
        self::assertNotNull($anchor);
        self::assertSame('#' . self::ID_WITH_ALLOWED_ELEMENTS_ONLY, $anchor->getAttribute('href'));
        foreach ($anchor->childNodes as $childNode) {
            self::assertContains($childNode->nodeName, ['#text', 'span', 'b', 'strong', 'i']);
        }
    }

    /**
     * @test
     */
    public function I_can_navigate_to_every_calculation_as_it_has_its_id_with_anchor(): void
    {
        $document = $this->getHtmlDocument();
        $calculations = $document->getElementsByClassName(HtmlHelper::CLASS_CALCULATION);
        if (\count($calculations) === 0 && !$this->isSkeletonChecked()) {
            self::assertFalse(false, 'No calculations in current document');

            return;
        }
        self::assertNotEmpty($calculations);
        $allowedCalculationIdPrefixes = $this->getTestsConfiguration()->getAllowedCalculationIdPrefixes();
        $allowedCalculationIdPrefixesRegexp = $this->toRegexpOr($allowedCalculationIdPrefixes);
        $allowedCalculationIdConstantLikePrefixes = \array_map(function (string $allowedPrefix) {
            return StringTools::toConstantLikeValue($allowedPrefix);
        }, $allowedCalculationIdPrefixes);
        $allowedCalculationIdConstantLikePrefixesRegexp = $this->toRegexpOr($allowedCalculationIdConstantLikePrefixes);
        foreach ($calculations as $calculation) {
            self::assertNotEmpty($calculation->id, 'Missing ID for calculation: ' . \trim($calculation->innerHTML));
            self::assertRegExp("~^($allowedCalculationIdPrefixesRegexp) ~u", $calculation->getAttribute('data-original-id'));
            self::assertRegExp("~^($allowedCalculationIdConstantLikePrefixesRegexp)_~u", $calculation->id);
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

    /**
     * @test
     */
    public function Calculation_does_not_have_another_calculation_inside(): void
    {
        $document = $this->getHtmlDocument();
        $calculations = $document->getElementsByClassName(HtmlHelper::CLASS_CALCULATION);
        if (\count($calculations) === 0 && !$this->isSkeletonChecked()) {
            self::assertFalse(false, 'No calculations in current document');

            return;
        }
        self::assertNotEmpty($calculations);
        foreach ($calculations as $calculation) {
            foreach ($calculation->children as $child) {
                $innerCalculations = $child->getElementsByClassName(HtmlHelper::CLASS_CALCULATION);
                self::assertCount(
                    0,
                    $innerCalculations,
                    'Calculation should not has another calculation inside: ' . $calculation->outerHTML
                );
            }
        }
    }

    /**
     * @test
     */
    public function Links_to_altar_uses_https(): void
    {
        $linksToAltar = [];
        foreach ($this->getExternalAnchors() as $link) {
            if (!\strpos($link, 'altar.cz')) {
                continue;
            }
            $linksToAltar[] = $link;
        }
        if (!$this->isSkeletonChecked() && !$this->getTestsConfiguration()->hasLinksToAltar()) {
            self::assertCount(0, $linksToAltar, 'No link to Altar expected according to tests config');

            return;
        }
        self::assertNotEmpty($linksToAltar, 'Expected some links to Altar');
        foreach ($linksToAltar as $linkToAltar) {
            self::assertStringStartsWith('https', $linkToAltar, "Every link to Altar should be via https: '$linkToAltar'");
        }
    }

    /**
     * @test
     */
    public function No_links_point_to_local_hosts(): void
    {
        $urlsWithLocalHosts = [];
        /** @var Element $anchor */
        foreach ($this->getHtmlDocument(['mode' => 'prod' /* do not turn links to local */])->getElementsByTagName('a') as $anchor) {
            $href = $anchor->getAttribute('href');
            self::assertNotEmpty($href);
            $parsedUrl = \parse_url($href);
            $hostname = $parsedUrl['host'] ?? null;
            if ($hostname === null) { // local link with anchor or query only
                continue;
            }
            if (\preg_match('~[.]loc#~', $hostname) || \gethostbyname($hostname) === '127.0.0.1') {
                $urlsWithLocalHosts[] = $anchor->outerHTML;
            }
        }
        self::assertCount(0, $urlsWithLocalHosts, "There are forgotten local URLs \n" . \implode(",\n", $urlsWithLocalHosts));
    }

    private function getPostDataToFetchContent(bool $isDrdPlus): array
    {
        return $isDrdPlus ? ['trial' => 1] : [];
    }

    private function getExternalAnchors(): array
    {
        static $externalAnchors = [];
        if (!$externalAnchors) {
            $html = $this->getHtmlDocument();
            /** @var Element $anchor */
            foreach ($html->getElementsByTagName('a') as $anchor) {
                $link = $anchor->getAttribute('href');
                if (\preg_match('~^(http|//)~', $link)) {
                    $externalAnchors[] = $link;
                }
            }
            $externalAnchors[] = $this->getTestsConfiguration()->getExpectedPublicUrl();
        }

        return $externalAnchors;
    }

    /**
     * @test
     */
    public function All_anchors_point_to_syntactically_valid_links(): void
    {
        foreach ($this->getLicenceSwitchers() as $licenceSwitcher) {
            $licenceSwitcher();
            $invalidAnchors = $this->parseInvalidAnchors($this->getContent());
            self::assertCount(
                0,
                $invalidAnchors,
                'Some anchors from content points to invalid links ' . implode(',', $invalidAnchors)
            );
            $invalidAnchors = $this->parseInvalidAnchors($this->getPassContent());
            self::assertCount(
                0,
                $invalidAnchors,
                'Some anchors from ownership confirmation points to invalid links ' . \implode(',', $invalidAnchors)
            );
        }
    }

    /**
     * @param string $content
     * @return array
     */
    private function parseInvalidAnchors(string $content): array
    {
        \preg_match_all('~(?<invalidAnchors><a[^>]+href="(?:(?![#?]|https?|[.]?/|mailto).)+[^>]+>)~', $content, $matches);

        return $matches['invalidAnchors'];
    }

    /**
     * @test
     */
    public function I_can_go_directly_to_eshop_item_page(): void
    {
        if (!$this->isSkeletonChecked() && !$this->getTestsConfiguration()->canBeBoughtOnEshop()) {
            self::assertFalse(false);

            return;
        }
        $eshopUrl = $this->getConfiguration()->getEshopUrl();
        self::assertRegExp('~^https://obchod\.altar\.cz/[^/]+\.html$~', $eshopUrl);
        self::assertSame($eshopUrl, $this->getLinkToEshop()->getAttribute('href'), 'Expected different link to e-shop');
    }

    private function getLinkToEshop(): Element
    {
        $body = $this->getHtmlDocument()->body;
        $rulesAuthorsElements = $body->getElementsByClassName(HtmlHelper::CLASS_RULES_AUTHORS);
        self::assertGreaterThan(
            0,
            $rulesAuthorsElements->count(),
            sprintf(
                'Link to eshop expected in %s as %s configuration says',
                HtmlHelper::CLASS_RULES_AUTHORS,
                TestsConfiguration::CAN_BE_BOUGHT_ON_ESHOP
            )
        );
        self::assertCount(
            1,
            $rulesAuthorsElements,
            \sprintf("Expected one '%s' class, got %d of them", HtmlHelper::CLASS_RULES_AUTHORS, $rulesAuthorsElements->count())
        );
        $rulesAuthors = $rulesAuthorsElements->current();
        $lastElement = $rulesAuthors->lastElementChild;
        self::assertSame(
            'a',
            $lastElement->tagName,
            'Expected link to eshop to be the last child of ' . HtmlHelper::CLASS_RULES_AUTHORS
        );
        $href = $lastElement->getAttribute('href');
        $host = \parse_url($href, \PHP_URL_HOST);
        if ($host === 'obchod.altar.cz') {
            return $lastElement;
        }
        self::fail(
            sprintf(
                'Missing a link to eshop obchod.altar.cz in ' . HtmlHelper::CLASS_RULES_AUTHORS,
                $rulesAuthors->outerHTML
            )
        );
        throw new \RuntimeException('Something simply can not happen');
    }

    /**
     * @test
     */
    public function Links_to_vukogvazd_uses_https(): void
    {
        $linksToVukogvazd = [];
        foreach ($this->getExternalAnchors() as $link) {
            if (\strpos($link, 'vukogvazd.cz')) {
                $linksToVukogvazd[] = $link;
            }
        }
        if (\count($linksToVukogvazd) === 0) {
            self::assertFalse(false, 'No links to Vukogvazd have been found');
        } else {
            foreach ($linksToVukogvazd as $linkToVukogvazd) {
                self::assertStringStartsWith('https', $linkToVukogvazd, "Every link to vukogvazd should be via https: '$linkToVukogvazd'");
            }
        }
    }

    /**
     * @test
     */
    public function Character_sheet_comes_from_drdplus_info(): void
    {
        $linksToCharacterSheet = [];
        foreach ($this->getExternalAnchors() as $link) {
            $link = HtmlHelper::turnToLocalLink($link);
            if (\strpos($link, 'charakternik.pdf')) {
                $linksToCharacterSheet[] = $link;
            }
        }
        if (!$this->getTestsConfiguration()->hasCharacterSheet()) {
            self::assertCount(0, $linksToCharacterSheet, 'No links to PDF character sheet expected');

            return;
        }
        self::assertGreaterThan(0, \count($linksToCharacterSheet), 'PDF character sheet is missing');
        $expectedOriginalLink = 'https://www.drdplus.info/pdf/charakternik.pdf';
        $expectedLink = HtmlHelper::turnToLocalLink($expectedOriginalLink);
        foreach ($linksToCharacterSheet as $linkToCharacterSheet) {
            self::assertSame(
                $expectedLink,
                $linkToCharacterSheet,
                "Every link to PDF character sheet should lead to $expectedOriginalLink"
            );
        }
    }

    /**
     * @test
     */
    public function Journal_comes_from_drdplus_info(): void
    {
        $linksToJournal = [];
        foreach ($this->getExternalAnchors() as $link) {
            $link = HtmlHelper::turnToLocalLink($link);
            if (\preg_match('~/denik_\w+\.pdf$~', $link)) {
                $linksToJournal[] = $link;
            }
        }
        if (!$this->getTestsConfiguration()->hasLinksToJournals() && !$this->getTestsConfiguration()->hasLinkToSingleJournal()) {
            self::assertCount(0, $linksToJournal, 'No links to PDF journal expected');

            return;
        }
        self::assertGreaterThan(0, \count($linksToJournal), 'PDF journals are missing');
        if ($this->isSkeletonChecked() || !$this->getTestsConfiguration()->hasLinkToSingleJournal()) {
            foreach ($linksToJournal as $linkToJournal) {
                self::assertRegExp(
                    '~^' . \preg_quote(HtmlHelper::turnToLocalLink('https://www.drdplus.info'), '~') . '/pdf/deniky/denik_\w+[.]pdf$~',
                    $linkToJournal,
                    'Every link to PDF journal should lead to https://www.drdplus.info/pdf/deniky/denik_foo.pdf'
                );
            }

            return;
        }
        self::assertTrue($this->getTestsConfiguration()->hasLinksToJournals());
        $expectedOriginalLink = $this->getExpectedLinkToJournal();
        $expectedLink = HtmlHelper::turnToLocalLink($expectedOriginalLink);
        foreach ($linksToJournal as $linkToJournal) {
            self::assertSame(
                $expectedLink,
                $linkToJournal,
                "Every link to PDF journal should lead to $expectedOriginalLink"
            );
        }
    }

    private function getExpectedLinkToJournal(): string
    {
        return 'https://www.drdplus.info/pdf/deniky/denik_' . StringTools::toConstantLikeValue($this->getProfessionName()) . '.pdf';
    }

    private function getProfessionName(): string
    {
        $currentPageTitle = $this->getCurrentPageTitle();
        self::assertSame(
            1,
            \preg_match('~\s(?<lastWord>\w+)$~u', $currentPageTitle, $matches),
            "No last word found in '$currentPageTitle'"
        );
        $lastWord = $matches['lastWord'];

        return \rtrim($lastWord, 'aeiouy');
    }

    /**
     * @test
     */
    public function Buttons_should_not_have_links_inside(): void
    {
        foreach ($this->getLicenceSwitchers() as $licenceSwitcher) {
            $licenceSwitcher();
            $buttons = $this->getHtmlDocument()->getElementsByTagName('button');
            if ($buttons->count() === 0 && !$this->isSkeletonChecked()) {
                self::assertCount(0, $buttons, 'Simply no buttons');

                return;
            }
            self::assertNotEmpty($buttons, 'Some buttons expected in a skeleton to test');
            foreach ($buttons as $button) {
                $buttonAnchors = $button->getElementsByTagName('a');
                self::assertCount(0, $buttonAnchors, 'No anchors expected in button: ' . $button->outerHTML);
            }
        }
    }
}