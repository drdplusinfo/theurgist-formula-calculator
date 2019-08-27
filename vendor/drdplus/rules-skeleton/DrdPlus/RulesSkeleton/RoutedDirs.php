<?php declare(strict_types=1);

namespace DrdPlus\RulesSkeleton;

class RoutedDirs extends Dirs
{
    /** @var PathProvider */
    private $pathProvider;
    /** @var string */
    private $relativeWebRoot;

    public function __construct(string $projectRoot, PathProvider $pathProvider)
    {
        parent::__construct($projectRoot);
        $this->pathProvider = $pathProvider;
    }

    public function getWebRoot(): string
    {
        $webRoot = parent::getWebRoot();
        if ($this->getRelativeWebRoot() !== '') {
            $webRoot .= '/' . $this->getRelativeWebRoot();
        }
        return $webRoot;
    }

    protected function getRelativeWebRoot(): string
    {
        if ($this->relativeWebRoot === null) {
            $this->relativeWebRoot = $this->unifyRelativePath($this->pathProvider->getPath());
        }
        return $this->relativeWebRoot;
    }

    protected function unifyRelativePath(string $path): string
    {
        $path = str_replace('\\', '/', $path);
        return trim($path, '/');
    }

}