<?php
declare(strict_types=1);

namespace DrdPlus\RulesSkeleton;

use Granam\WebContentBuilder\HtmlDocument;
use Gt\Dom\Element;

class HtmlHelper extends \Granam\WebContentBuilder\HtmlHelper
{
    public const ID_AUTHORS = 'autori';
    public const ID_MENU = 'menu';
    public const ID_MENU_WRAPPER = 'menu_wrapper';
    public const ID_META_REDIRECT = 'meta_redirect';
    public const ID_DEBUG_CONTACTS = 'debug_contacts';
    public const ID_HOME_BUTTON = 'home_button';
    public const ID_TABLE_OF_CONTENTS = 'table_of_contents';
    public const CLASS_RULES_AUTHORS = 'rules-authors';
    public const CLASS_CALCULATION = 'calculation';
    public const CLASS_COVERED_BY_CODE = 'covered-by-code';
    public const CLASS_QUOTE = 'quote';
    public const CLASS_BACKGROUND_IMAGE = 'background-image';
    public const CLASS_GENERIC = 'generic';
    public const CLASS_NOTE = 'note';
    public const CLASS_EXCLUDED = 'excluded';
    public const CLASS_INVISIBLE = 'invisible';
    public const CLASS_DELIMITER = 'delimiter';
    public const CLASS_CONTENT = 'content';
    public const CLASS_EXTERNAL_URL = 'external-url';
    public const CLASS_HIDDEN = 'hidden';
    public const CLASS_SOURCE_CODE_TITLE = 'source-code-title';
    public const DATA_CACHE_STAMP = 'data-cache-stamp';
    public const DATA_CACHED_AT = 'data-cached-at';
    public const DATA_HAS_MARKED_EXTERNAL_URLS = 'data-has-marked-external-urls';

    /**
     * Turn link into local version
     * @param string $link
     * @return string
     */
    public static function turnToLocalLink(string $link): string
    {
        return \preg_replace('~https?://((?:[^.]+[.])*)drdplus\.info~', 'http://$1drdplus.loc', $link);
    }

    public static function createFromGlobals(Dirs $dirs, Environment $environment): HtmlHelper
    {
        return new static(
            $dirs,
            $environment,
            !empty($_GET['mode']) && \strpos(\trim($_GET['mode']), 'dev') === 0,
            !empty($_GET['mode']) && \strpos(\trim($_GET['mode']), 'prod') === 0,
            !empty($_GET['hide']) && \strpos(\trim($_GET['hide']), 'cover') === 0
        );
    }

    /** @var Environment */
    private $environment;
    /** @var bool */
    private $inDevMode;
    /** @var bool */
    private $inForcedProductionMode;
    /** @var bool */
    private $shouldHideCovered;

    public function __construct(Dirs $dirs, Environment $environment, bool $inDevMode, bool $inForcedProductionMode, bool $shouldHideCovered)
    {
        parent::__construct($dirs);
        $this->environment = $environment;
        $this->inDevMode = $inDevMode;
        $this->inForcedProductionMode = $inForcedProductionMode;
        $this->shouldHideCovered = $shouldHideCovered;
    }

    /**
     * @param HtmlDocument $htmlDocument
     * @param array|string[] $requiredIds filter of required tables by their IDs
     * @return array|Element[]
     */
    public function findTablesWithIds(HtmlDocument $htmlDocument, array $requiredIds = []): array
    {
        $requiredIds = \array_filter($requiredIds, 'trim');
        $requiredIds = \array_unique($requiredIds);
        $unifiedRequiredIds = [];
        foreach ($requiredIds as $requiredId) {
            if ($requiredId === '') {
                continue;
            }
            $unifiedRequiredId = static::toId($requiredId);
            $unifiedRequiredIds[$unifiedRequiredId] = $unifiedRequiredId;
        }
        $tablesWithIds = [];
        /** @var Element $table */
        foreach ($htmlDocument->getElementsByTagName('table') as $table) {
            $tableId = $this->getFirstIdFrom($table);
            if ($tableId === null) {
                continue;
            }
            $unifiedTableId = static::toId($tableId);
            $tablesWithIds[$unifiedTableId] = $table;
        }
        if (!$unifiedRequiredIds) {
            return $tablesWithIds; // all of them, no filter
        }

        return \array_intersect_key($tablesWithIds, $unifiedRequiredIds);
    }

    /**
     * @param HtmlDocument $htmlDocument
     * @return HtmlDocument
     */
    public function makeDrdPlusLinksLocal(HtmlDocument $htmlDocument): HtmlDocument
    {
        /** @var Element $anchor */
        foreach ($htmlDocument->getElementsByTagName('a') as $anchor) {
            $anchor->setAttribute('href', static::turnToLocalLink($anchor->getAttribute('href')));
        }
        /** @var Element $iFrame */
        foreach ($htmlDocument->getElementsByTagName('iframe') as $iFrame) {
            $iFrame->setAttribute('src', static::turnToLocalLink($iFrame->getAttribute('src')));
            $iFrame->setAttribute('id', \str_replace('drdplus.info', 'drdplus.loc', $iFrame->getAttribute('id')));
        }

        return $htmlDocument;
    }

    public function markExternalLinksByClass(HtmlDocument $htmlDocument): HtmlDocument
    {
        /** @var Element $anchor */
        foreach ($htmlDocument->getElementsByTagName('a') as $anchor) {
            if (!$anchor->classList->contains(self::CLASS_INTERNAL_URL)
                && \preg_match('~^(https?:)?//[^#]~', $anchor->getAttribute('href') ?? '')
            ) {
                $anchor->classList->add(self::CLASS_EXTERNAL_URL);
            }
        }
        $htmlDocument->body->setAttribute(self::DATA_HAS_MARKED_EXTERNAL_URLS, '1');

        return $htmlDocument;
    }

    /**
     * @param HtmlDocument $htmlDocument
     * @return array|Element[]
     */
    protected function getExternalAnchors(HtmlDocument $htmlDocument): array
    {
        $externalAnchors = [];
        foreach ($htmlDocument->getElementsByTagName('a') as $anchor) {
            if ($this->isAnchorExternal($anchor)) {
                $externalAnchors[] = $anchor;
            }
        }

        return $externalAnchors;
    }

    protected function isAnchorExternal(Element $anchor): bool
    {
        if ($anchor->tagName !== 'a') {
            throw new Exceptions\ExpectedAnchorElement(
                sprintf('Expected anchor element, got %s (%s)', $anchor->tagName, $anchor->innerHTML)
            );
        }

        return !$anchor->classList->contains(self::CLASS_INTERNAL_URL)
            && ($anchor->classList->contains(self::CLASS_EXTERNAL_URL) || $this->isLinkExternal($anchor->getAttribute('href')));
    }

    /**
     * @param HtmlDocument $htmlDocument
     * @return HtmlDocument
     * @throws \LogicException
     */
    public function injectIframesWithRemoteTables(HtmlDocument $htmlDocument): HtmlDocument
    {
        $remoteDrdPlusLinks = [];
        /** @var Element $anchor */
        foreach ($htmlDocument->getElementsByTagName('a') as $anchor) {
            if ($anchor->classList->contains(self::CLASS_INTERNAL_URL)
                || !$anchor->classList->contains(self::CLASS_EXTERNAL_URL)
                || !$this->isLinkExternal($anchor->getAttribute('href'))
            ) {
                continue;
            }
            if (!\preg_match(
                '~(?<protocol>(?:https?:)?//)(?<host>[[:alpha:]]+[.]drdplus[.](?:info|loc))/[^#]*#(?<tableId>tabulka_\w+)~',
                $anchor->getAttribute('href'), $matches)
            ) {
                continue;
            }
            $remoteDrdPlusLinks[$matches['host']][$matches['protocol']][] = $matches['tableId'];
        }
        if (\count($remoteDrdPlusLinks) === 0) {
            return $htmlDocument;
        }
        $body = $htmlDocument->body;
        foreach ($remoteDrdPlusLinks as $remoteDrdPlusHost => $protocolToTableIds) {
            foreach ($protocolToTableIds as $protocol => $tableIds) {
                $iFrame = $htmlDocument->createElement('iframe');
                $body->appendChild($iFrame);
                $iFrame->setAttribute('id', $remoteDrdPlusHost); // we will target that iframe via JS by remote host name
                $iFrame->setAttribute(
                    'src',
                    "{$protocol}{$remoteDrdPlusHost}/?tables=" . \htmlspecialchars(\implode(',', \array_unique($tableIds)))
                );
                $iFrame->setAttribute('class', static::CLASS_HIDDEN);
            }
        }

        return $htmlDocument;
    }

    public function addIdsToTables(HtmlDocument $htmlDocument): HtmlDocument
    {
        foreach ($htmlDocument->getElementsByTagName('caption') as $caption) {
            if ($this->getFirstIdFrom($caption) !== null) { // there is already some ID on this CAPTION or on some of its children
                continue;
            }
            $captionContent = \trim($caption->textContent);
            if ($captionContent === '') {
                continue;
            }
            $caption->setAttribute('id', $captionContent);
        }
        /** @var Element $headerCell */
        foreach ($htmlDocument->getElementsByTagName('th') as $headerCell) {
            if ($this->getFirstIdFrom($headerCell) !== null) { // there is already some ID on this TH or on some of its children
                continue;
            }
            $headerCellContent = \trim($headerCell->textContent);
            if ($headerCellContent === '') {
                continue;
            }
            if (\strpos($headerCellContent, 'Tabulka') === false) {
                continue;
            }
            $headerCell->setAttribute('id', $headerCellContent);
        }

        return $htmlDocument;
    }

    public function prepareSourceCodeLinks(HtmlDocument $htmlDocument): void
    {
        if (!$this->inDevMode) {
            foreach ($htmlDocument->getElementsByClassName(self::CLASS_SOURCE_CODE_TITLE) as $withSourceCode) {
                $withSourceCode->className = \str_replace(self::CLASS_SOURCE_CODE_TITLE, self::CLASS_HIDDEN, $withSourceCode->className);
                $withSourceCode->removeAttribute('data-source-code');
            }
        } else {
            foreach ($htmlDocument->getElementsByClassName(self::CLASS_SOURCE_CODE_TITLE) as $withSourceCode) {
                $withSourceCode->appendChild($sourceCodeLink = new Element('a', 'source code'));
                $sourceCodeLink->setAttribute('class', 'source-code');
                $sourceCodeLink->setAttribute('href', $withSourceCode->getAttribute('data-source-code'));
            }
        }
    }

    /**
     * @param HtmlDocument $html
     */
    public function resolveDisplayMode(HtmlDocument $html): void
    {
        if ($this->inDevMode) {
            $this->removeImages($html->body);
        } else {
            $this->removeClassesAboutCodeCoverage($html->body);
        }
        if (!$this->inDevMode || !$this->shouldHideCovered) {
            return;
        }
        $classesToHide = [self::CLASS_COVERED_BY_CODE, self::CLASS_QUOTE, self::CLASS_GENERIC, self::CLASS_NOTE, self::CLASS_EXCLUDED, self::CLASS_RULES_AUTHORS];
        foreach ($classesToHide as $classToHide) {
            foreach ($html->getElementsByClassName($classToHide) as $nodeToHide) {
                $nodeToHide->className = \str_replace($classToHide, self::CLASS_HIDDEN, $nodeToHide->className);
            }
        }
    }

    private function removeImages(Element $html): void
    {
        do {
            $somethingRemoved = false;
            /** @var Element $image */
            foreach ($html->getElementsByTagName('img') as $image) {
                $image->remove();
                $somethingRemoved = true;
            }
        } while ($somethingRemoved); // do not know why, but some nodes are simply skipped on first removal so have to remove them again
    }

    private function removeClassesAboutCodeCoverage(Element $html): void
    {
        $classesToRemove = [self::CLASS_COVERED_BY_CODE, self::CLASS_GENERIC, self::CLASS_EXCLUDED];
        foreach ($html->children as $child) {
            foreach ($classesToRemove as $classToRemove) {
                $child->classList->remove($classToRemove);
            }
            $this->removeClassesAboutCodeCoverage($child);
        }
    }

    /**
     * @param HtmlDocument $htmlDocument
     * @return HtmlDocument
     */
    public function makeExternalDrdPlusLinksLocal(HtmlDocument $htmlDocument): HtmlDocument
    {
        foreach ($this->getExternalAnchors($htmlDocument) as $externalAnchor) {
            $externalAnchor->setAttribute('href', self::turnToLocalLink($externalAnchor->getAttribute('href')));
        }
        foreach ($this->getInternalAnchors($htmlDocument) as $internalAnchor) {
            $internalAnchor->setAttribute('href', self::turnToLocalLink($internalAnchor->getAttribute('href')));
        }
        /** @var Element $iFrame */
        foreach ($htmlDocument->getElementsByTagName('iframe') as $iFrame) {
            $iFrame->setAttribute('src', self::turnToLocalLink($iFrame->getAttribute('src')));
            $iFrame->setAttribute('id', \str_replace('drdplus.info', 'drdplus.loc', $iFrame->getAttribute('id')));
        }

        return $htmlDocument;
    }

    protected function getInternalAnchors(HtmlDocument $htmlDocument): array
    {
        $internalAnchors = [];
        foreach ($htmlDocument->getElementsByTagName('a') as $anchor) {
            if (!$this->isAnchorExternal($anchor)) {
                $internalAnchors[] = $anchor;
            }
        }

        return $internalAnchors;
    }

    public function isInProduction(): bool
    {
        return $this->inForcedProductionMode
            || (!$this->environment->isOnDevEnvironment() && (!$this->environment->isCliRequest() && !$this->environment->isOnLocalhost()));
    }

    public function replaceDiacriticsFromDrdPlusAnchorHashes(HtmlDocument $htmlDocument): HtmlDocument
    {
        $this->replaceDiacriticsFromAnchorHashes(
            $htmlDocument,
            '~(^$|drdplus[.](?:loc|info))~',
            '~blog[.]drdplus[.](?:loc|info)~'
        );

        return $htmlDocument;
    }
}