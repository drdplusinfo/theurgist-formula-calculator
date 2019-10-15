<?php declare(strict_types=1);

namespace DrdPlus\RulesSkeleton;

use Granam\Strict\Object\StrictObject;

class ContentIrrelevantRequestAliases extends StrictObject
{
    /**
     * @var ContentIrrelevantRequestAlias[]
     */
    private $contentIrrelevantRequestAliases = [];

    /**
     * @param ContentIrrelevantRequestAlias[] $contentIrrelevantRequestAliases
     */
    public function __construct(array $contentIrrelevantRequestAliases)
    {
        foreach ($contentIrrelevantRequestAliases as $contentIrrelevantRequestAlias) {
            $this->addContentIrrelevantRequestAlias($contentIrrelevantRequestAlias);
        }
    }

    private function addContentIrrelevantRequestAlias(ContentIrrelevantRequestAlias $contentIrrelevantRequestAlias)
    {
        $this->contentIrrelevantRequestAliases[] = $contentIrrelevantRequestAlias;
    }

    public function getTruePath(string $currentPath, array $currentGetParameters): string
    {
        if ($currentPath === '/' && $currentGetParameters === []) {
            return $currentPath;
        }
        foreach ($this->contentIrrelevantRequestAliases as $contentIrrelevantRequestAlias) {
            // if both alias path and alias parameters match, then we have found a true, de-aliased path
            if ($currentPath === $contentIrrelevantRequestAlias->getAliasPath()
                && $currentGetParameters === $contentIrrelevantRequestAlias->getAliasGetParameters()
            ) {
                return $contentIrrelevantRequestAlias->getTruePath();
            }
        }
        return $currentPath;
    }

    public function getTrueParameters(string $currentPath, array $currentGetParameters): array
    {
        if ($currentPath === '/' && $currentGetParameters === []) {
            return $currentGetParameters;
        }
        foreach ($this->contentIrrelevantRequestAliases as $contentIrrelevantRequestAlias) {
            // if both alias path and alias parameters match, then we have found a true, de-aliased path
            if ($currentPath === $contentIrrelevantRequestAlias->getAliasPath()
                && $currentGetParameters === $contentIrrelevantRequestAlias->getAliasGetParameters()
            ) {
                return $contentIrrelevantRequestAlias->getTrueParameters();
            }
        }
        return $currentGetParameters;
    }
}
