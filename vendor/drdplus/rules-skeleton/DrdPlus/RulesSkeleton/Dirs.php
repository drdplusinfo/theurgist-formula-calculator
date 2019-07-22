<?php
declare(strict_types=1);

namespace DrdPlus\RulesSkeleton;

class Dirs extends \Granam\WebContentBuilder\Dirs
{
    /** @var string */
    private $cacheRoot;
    /** @var string */
    private $pdfRoot;
    /** @var string */
    private $relativeWebRoot;

    public function __construct(string $projectRoot, string $relativeWebRoot = '')
    {
        parent::__construct($projectRoot);
        $this->cacheRoot = $projectRoot . '/cache/' . \PHP_SAPI;
        $this->pdfRoot = $projectRoot . '/pdf';
        $this->relativeWebRoot = $this->unifyRelativePath($relativeWebRoot);
    }

    protected function unifyRelativePath(string $path): string
    {
        $path = str_replace('\\', '/', $path);
        return trim($path, '/');
    }

    public function getWebRoot(): string
    {
        $webRoot = parent::getWebRoot();
        if ($this->relativeWebRoot !== '') {
            $webRoot .= '/' . $this->relativeWebRoot;
        }
        return $webRoot;
    }

    public function getCacheRoot(): string
    {
        return $this->cacheRoot;
    }

    public function getPdfRoot(): string
    {
        return $this->pdfRoot;
    }
}