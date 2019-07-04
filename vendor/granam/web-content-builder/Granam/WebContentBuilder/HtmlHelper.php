<?php declare(strict_types=1);

namespace Granam\WebContentBuilder;

use Granam\Strict\Object\StrictObject;
use Granam\String\StringTools;
use Gt\Dom\Element;

class HtmlHelper extends StrictObject
{

    public const CLASS_INVISIBLE_ID = 'invisible-id';
    public const DATA_ORIGINAL_ID = 'data-original-id';
    public const DATA_ORIGINAL_FOR = 'data-original-for';
    public const CLASS_INTERNAL_URL = 'internal-url';

    /** @var Dirs */
    private $dirs;

    /**
     * Turn link into local version
     * @param string $name
     * @return string
     * @throws \Granam\WebContentBuilder\Exceptions\NameToCreateHtmlIdFromIsEmpty
     */
    public static function toId(string $name): string
    {
        if ($name === '') {
            throw new Exceptions\NameToCreateHtmlIdFromIsEmpty('Expected some name to create HTML ID from');
        }

        return StringTools::toSnakeCaseId($name);
    }

    public function __construct(Dirs $dirs)
    {
        $this->dirs = $dirs;
    }

    public function addIdsToHeadings(HtmlDocument $htmlDocument): HtmlDocument
    {
        $elementNames = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
        foreach ($elementNames as $elementName) {
            /** @var Element $headerCell */
            foreach ($htmlDocument->getElementsByTagName($elementName) as $headerCell) {
                if ($headerCell->getAttribute('id')) {
                    continue;
                }
                $id = false;
                /** @var \DOMNode $childNode */
                foreach ($headerCell->childNodes as $childNode) {
                    if ($childNode->nodeType === \XML_TEXT_NODE) {
                        $id = \trim($childNode->nodeValue);
                        break;
                    }
                }
                if (!$id) {
                    continue;
                }
                $headerCell->setAttribute('id', $id);
            }
        }

        return $htmlDocument;
    }

    public function unifyIds(HtmlDocument $htmlDocument): HtmlDocument
    {
        $htmlDocument = $this->unifyIdsOnly($htmlDocument);
        $htmlDocument = $this->unifyForInLabels($htmlDocument);

        return $htmlDocument;
    }

    private function unifyIdsOnly(HtmlDocument $htmlDocument): HtmlDocument
    {
        foreach ($this->getElementsWithId($htmlDocument) as $id => $elementWithId) {
            $idWithoutDiacritics = static::toId($id);
            if ($idWithoutDiacritics === $id) {
                continue;
            }
            $elementWithId->setAttribute(self::DATA_ORIGINAL_ID, $id);
            $elementWithId->setAttribute('id', $this->sanitizeId($idWithoutDiacritics));

            $invisibleIdElement = new Element('span');
            $elementWithId->appendChild($invisibleIdElement);
            $invisibleIdElement->setAttribute('id', $this->sanitizeId($id));
            $invisibleIdElement->className = self::CLASS_INVISIBLE_ID;
        }

        return $htmlDocument;
    }

    private function sanitizeId(string $id): string
    {
        return str_replace('#', '_', $id);
    }

    private function unifyForInLabels(HtmlDocument $htmlDocument): HtmlDocument
    {
        foreach ($this->getLabelsWithFor($htmlDocument) as $id => $labelWithFor) {
            $idWithoutDiacritics = static::toId($id);
            if ($idWithoutDiacritics === $id) {
                continue;
            }
            $labelWithFor->setAttribute(self::DATA_ORIGINAL_FOR, $id);
            $labelWithFor->setAttribute('for', $this->sanitizeId($idWithoutDiacritics));
        }

        return $htmlDocument;
    }

    /**
     * @param HtmlDocument $htmlDocument
     * @return array|Element[]
     */
    private function getLabelsWithFor(HtmlDocument $htmlDocument): array
    {
        $labels = $htmlDocument->getElementsByTagName('label');
        $labelsWithFor = [];
        foreach ($labels as $label) {
            $id = (string)$label->getAttribute('for');
            if ($id !== '') {
                $labelsWithFor[$id] = $label;
            }
        }

        return $labelsWithFor;
    }

    public function replaceDiacriticsFromAnchorHashes(
        HtmlDocument $htmlDocument,
        string $includingUrlPattern = null,
        string $excludingUrlPattern = null
    ): HtmlDocument
    {
        $this->replaceDiacriticsFromChildrenAnchorHashes(
            $htmlDocument->getElementsByTagName('a'),
            $includingUrlPattern,
            $excludingUrlPattern
        );

        return $htmlDocument;
    }

    private function replaceDiacriticsFromChildrenAnchorHashes(
        \Traversable $anchors,
        ?string $includingUrlPattern,
        ?string $excludingUrlPattern
    ): void
    {
        /** @var Element $anchor */
        foreach ($anchors as $anchor) {
            // recursion
            $this->replaceDiacriticsFromChildrenAnchorHashes($anchor->getElementsByTagName('a'), $includingUrlPattern, $excludingUrlPattern);
            $href = (string)$anchor->getAttribute('href');
            $hash = $this->parseHash($href, $includingUrlPattern, $excludingUrlPattern);
            if ($hash === '') {
                continue;
            }
            $hashWithoutDiacritics = static::toId($hash);
            if ($hashWithoutDiacritics === $hash) {
                continue;
            }
            $hrefWithoutDiacritics = str_replace('#' . $hash, '#' . $hashWithoutDiacritics, $href);
            $anchor->setAttribute('href', $hrefWithoutDiacritics);
        }
    }

    private function parseHash(string $href, string $urlMatchingPattern = null, string $urlExcludingPattern = null): string
    {
        if (!$href) {
            return '';
        }
        $hashPosition = \strpos($href, '#');
        if ($hashPosition === false) {
            return '';
        }
        if ($urlMatchingPattern !== null || $urlExcludingPattern !== null) {
            $link = \substr($href, 0, $hashPosition);
            if ($urlMatchingPattern !== null && !\preg_match($urlMatchingPattern, $link)) {
                return '';
            }
            if ($urlExcludingPattern !== null && \preg_match($urlExcludingPattern, $link)) {
                return '';
            }
        }

        return (string)\substr($href, $hashPosition + 1);
    }

    public function addAnchorsToIds(HtmlDocument $htmlDocument): HtmlDocument
    {
        foreach ($this->getElementsWithId($htmlDocument) as $id => $elementWithId) {
            if (!in_array($elementWithId->nodeName, ['a', 'button'], true)
                && $elementWithId->getElementsByTagName('a')->length === 0 // already have some anchors, skip it to avoid wrapping them by another one
                && !$elementWithId->prop_get_classList()->contains(self::CLASS_INVISIBLE_ID)
            ) {
                $toMove = [];
                /** @var \DOMElement $childNode */
                foreach ($elementWithId->childNodes as $childNode) {
                    if (!in_array($childNode->nodeName, ['span', 'strong', 'b', 'i', '#text'], true)) {
                        break;
                    }
                    $toMove[] = $childNode;
                }
                if ($toMove) {
                    $anchorToSelf = new Element('a');
                    $elementWithId->replaceChild($anchorToSelf, $toMove[0]); // pairs anchor with parent element
                    $anchorToSelf->setAttribute('href', '#' . $id);
                    foreach ($toMove as $index => $item) {
                        $anchorToSelf->appendChild($item);
                    }
                }
            }
        }

        return $htmlDocument;
    }

    /**
     * @param HtmlDocument $htmlDocument
     * @return array|Element[]
     */
    private function getElementsWithId(HtmlDocument $htmlDocument): array
    {
        $elementsWithId = [];
        foreach ($this->getIds($htmlDocument) as $id) {
            $elementsWithId[$id] = $this->getElementWithId($id, $htmlDocument);
        }

        return $elementsWithId;
    }

    private function getElementWithId(string $id, HtmlDocument $htmlDocument): Element
    {
        $elementById = $htmlDocument->getElementById($id);
        if (!$elementById) {
            throw new Exceptions\ElementNotFoundById(
                \sprintf("No element has been found by ID '%s'", $id)
            );
        }

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $elementById;
    }

    /**
     * @param HtmlDocument $htmlDocument
     * @return array|string[]
     */
    private function getIds(HtmlDocument $htmlDocument): array
    {
        if (!\preg_match_all('~\Wid\s*=\s*"(?<ids>[^"]+)"~', $htmlDocument->body->prop_get_innerHTML(), $matches)) {
            return [];
        }

        return \array_map('html_entity_decode', $matches['ids']);
    }

    /**
     * @param HtmlDocument $htmlDocument
     * @throws \LogicException
     */
    public function externalLinksTargetToBlank(HtmlDocument $htmlDocument): void
    {
        /** @var Element $anchor */
        foreach ($htmlDocument->getElementsByTagName('a') as $anchor) {
            if (!$anchor->getAttribute('target')
                && !$anchor->classList->contains(self::CLASS_INTERNAL_URL)
                && \preg_match('~^(https?:)?//[^#]~', $anchor->getAttribute('href') ?? '')
            ) {
                $anchor->setAttribute('target', '_blank');
            }
        }
    }

    public function addVersionHashToAssets(HtmlDocument $htmlDocument): HtmlDocument
    {
        $documentRoot = $this->dirs->getProjectRoot();
        foreach ($htmlDocument->getElementsByTagName('img') as $image) {
            $this->addVersionToAsset($image, 'src', $documentRoot);
        }
        foreach ($htmlDocument->getElementsByTagName('link') as $link) {
            $this->addVersionToAsset($link, 'href', $documentRoot);
        }
        foreach ($htmlDocument->getElementsByTagName('script') as $script) {
            $this->addVersionToAsset($script, 'src', $documentRoot);
        }

        return $htmlDocument;
    }

    private function addVersionToAsset(Element $element, string $attributeName, string $masterDocumentRoot): void
    {
        $link = $element->getAttribute($attributeName);
        if ($this->isLinkExternal($link)) {
            return;
        }
        $absolutePath = $this->getAbsolutePath($link, $masterDocumentRoot);
        $hash = $this->getFileHash($absolutePath);
        $element->setAttribute($attributeName, $link . '?version=' . \urlencode($hash));
    }

    protected function isLinkExternal(string $link): bool
    {
        $urlParts = \parse_url($link);

        return !empty($urlParts['host']);
    }

    private function getAbsolutePath(string $relativePath, string $masterDocumentRoot): string
    {
        $relativePath = \ltrim($relativePath, '\\/');
        $absolutePath = $masterDocumentRoot . '/' . $relativePath;

        return str_replace('/./', '/', $absolutePath);
    }

    private function getFileHash(string $fileName): string
    {
        return \md5_file($fileName) ?: (string)\time(); // time is a fallback
    }

    public function getFirstIdFrom(Element $element): ?string
    {
        $id = (string)$element->getAttribute('id');
        if ($id !== '') {
            return $id;
        }
        foreach ($element->children as $child) {
            $id = $this->getFirstIdFrom($child);
            if ($id !== null) {
                return $id;
            }
        }

        return null;
    }
}