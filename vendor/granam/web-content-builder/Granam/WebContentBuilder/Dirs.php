<?php declare(strict_types=1);

namespace Granam\WebContentBuilder;

use Granam\Strict\Object\StrictObject;

class Dirs extends StrictObject
{
    /** @var string */
    private $projectRoot;
    /** @var string */
    private $webRoot;
    /** @var string */
    private $vendorRoot;
    /** @var string */
    private $jsRoot;
    /** @var string */
    private $cssRoot;

    public static function createFromGlobals()
    {
        $projectRootDir = '';
        foreach ([$GLOBALS['documentRoot'] ?? '', $_SERVER['PROJECT_DIR'] ?? '', $_SERVER['DOCUMENT_ROOT'] ?? ''] as $candidate) {
            $projectRootDir = $candidate;
            if ($projectRootDir !== '') {
                break;
            }
        }

        return new static($projectRootDir !== '' ? $projectRootDir : \getcwd());
    }

    public function __construct(string $projectRoot)
    {
        $this->projectRoot = $projectRoot;
        $this->populateSubRoots($projectRoot);
    }

    private function populateSubRoots(string $documentRoot): void
    {
        $this->webRoot = $documentRoot . '/web';
        $this->vendorRoot = $documentRoot . '/vendor';
        $this->jsRoot = $documentRoot . '/js';
        $this->cssRoot = $documentRoot . '/css';
    }

    public function getProjectRoot(): string
    {
        return $this->projectRoot;
    }

    public function getWebRoot(): string
    {
        return $this->webRoot;
    }

    public function getVendorRoot(): string
    {
        return $this->vendorRoot;
    }

    public function getJsRoot(): string
    {
        return $this->jsRoot;
    }

    public function getCssRoot(): string
    {
        return $this->cssRoot;
    }
}