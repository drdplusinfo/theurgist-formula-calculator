<?php
declare(strict_types=1);

namespace DrdPlus\RulesSkeleton;

class Dirs extends \Granam\WebContentBuilder\Dirs
{
    /** @var string */
    private $cacheRoot;
    /** @var string */
    private $pdfRoot;

    public function __construct(string $projectRoot)
    {
        parent::__construct($projectRoot);
        $this->cacheRoot = $projectRoot . '/cache/' . \PHP_SAPI;
        $this->pdfRoot = $projectRoot . '/pdf';
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