<?php declare(strict_types=1);

namespace DrdPlus\RulesSkeleton;

use Granam\Git\Git;
use Granam\Strict\Object\StrictObject;

abstract class Cache extends StrictObject
{
    // named parameters
    public const IN_PRODUCTION = true;
    public const NOT_IN_PRODUCTION = false;

    /** @var string */
    protected $projectRootDir;
    /** @var string */
    private $cacheRootDir;
    /** @var array|string[] */
    protected $cacheRoots;
    /** @var CurrentWebVersion */
    protected $currentWebVersion;
    /** @var Request */
    private $request;
    /** @var Git */
    private $git;
    /** @var bool */
    protected $isInProduction;
    /** @var ContentIrrelevantRequestAliases */
    private $contentIrrelevantRequestAliases;
    /** @var ContentIrrelevantParametersFilter */
    private $contentIrrelevantParametersFilter;

    /**
     * @param CurrentWebVersion $currentWebVersion
     * @param string $projectRootDir
     * @param string $cacheRootDir
     * @param Request $request
     * @param ContentIrrelevantRequestAliases $contentIrrelevantRequestAliases
     * @param ContentIrrelevantParametersFilter $contentIrrelevantParametersFilter
     * @param Git $git
     * @param bool $isInProduction
     * @throws \RuntimeException
     */
    public function __construct(
        CurrentWebVersion $currentWebVersion,
        string $projectRootDir,
        string $cacheRootDir,
        Request $request,
        ContentIrrelevantRequestAliases $contentIrrelevantRequestAliases,
        ContentIrrelevantParametersFilter $contentIrrelevantParametersFilter,
        Git $git,
        bool $isInProduction
    )
    {
        $this->currentWebVersion = $currentWebVersion;
        $this->projectRootDir = $projectRootDir;
        $this->cacheRootDir = $cacheRootDir;
        $this->request = $request;
        $this->contentIrrelevantRequestAliases = $contentIrrelevantRequestAliases;
        $this->contentIrrelevantParametersFilter = $contentIrrelevantParametersFilter;
        $this->git = $git;
        $this->isInProduction = $isInProduction;
    }

    /**
     * @return string
     */
    public function getCacheDir(): string
    {
        $currentVersion = $this->currentWebVersion->getCurrentMinorVersion();
        if (($this->cacheRoots[$currentVersion] ?? null) === null) {
            $cacheRoot = $this->cacheRootDir . '/' . $currentVersion;
            if (!\file_exists($cacheRoot)) {
                if (!@\mkdir($cacheRoot, 0775, true /* with parents */) && !\is_dir($cacheRoot)) {
                    throw new \RuntimeException('Can not create directory for page cache ' . $cacheRoot);
                }
                \chmod($cacheRoot, 0775); // because umask could suppress it
            }
            $this->cacheRoots[$currentVersion] = $cacheRoot;
        }

        return $this->cacheRoots[$currentVersion];
    }

    public function isInProduction(): bool
    {
        return $this->isInProduction;
    }

    protected function getCurrentRequestHash(): string
    {
        $filteredGetParameters = $this->contentIrrelevantParametersFilter
            ->removeContentIrrelevantParameters($this->request->getValuesFromGet());
        $contentRelevantPath = $this->contentIrrelevantRequestAliases->getTruePath(
            $this->request->getPath(),
            $filteredGetParameters
        );
        $contentRelevantGetParameters = $this->contentIrrelevantRequestAliases->getTrueParameters(
            $this->request->getPath(),
            $filteredGetParameters
        );
        return \md5($contentRelevantPath . \serialize($contentRelevantGetParameters));
    }

    /**
     * @return bool
     * @throws \RuntimeException
     */
    public function isCacheValid(): bool
    {
        $cacheParameter = $this->request->getValue(Request::CACHE) ?? '';

        return ($cacheParameter === '' || !\in_array($cacheParameter, [Request::DISABLE, 'disabled', '0'], true))
            && \is_readable($this->getCacheFileName());
    }

    public function getCacheId(): string
    {
        return $this->getCurrentContentHash() . '_' . $this->getCurrentRequestHash();
    }

    private function getCacheFileName(): string
    {
        return $this->getCacheDir() . "/{$this->getCacheId()}.html";
    }

    private function getCurrentContentHash(): string
    {
        return sprintf(
            '%s_%s_%s',
            $this->currentWebVersion->getCurrentPatchVersion(),
            $this->currentWebVersion->getCurrentCommitHash(),
            $this->getGitStamp()
        );
    }

    private function getGitStamp(): string
    {
        if ($this->isInProduction()) {
            return 'production';
        }
        $gitStatus = $this->git->getGitStatus($this->projectRootDir);
        $diffAgainstOriginMaster = $this->git->getDiffAgainstOrigin($this->projectRootDir);
        $gitStatusImploded = \implode($gitStatus);
        $diffAgainstOriginMasterImploded = \implode($diffAgainstOriginMaster);

        return \md5($gitStatusImploded . $diffAgainstOriginMasterImploded);
    }

    /**
     * @return string
     * @throws \DrdPlus\RulesSkeleton\Exceptions\CanNotReadCachedContent
     */
    public function getCachedContent(): string
    {
        $cachedContent = \file_get_contents($this->getCacheFileName());
        if ($cachedContent === false) {
            throw new Exceptions\CanNotReadCachedContent("Can not read cached content from '{$this->getCacheFileName()}'");
        }

        return $cachedContent;
    }

    /**
     * @param string $content
     * @throws \DrdPlus\RulesSkeleton\Exceptions\CanNotSaveContentForDebug
     * @throws \DrdPlus\RulesSkeleton\Exceptions\CanNotChangeAccessToFileWithContentForDebug
     */
    public function saveContentForDebug(string $content): void
    {
        $cacheDebugFileName = $this->getCacheDebugFileName();
        if (!\file_put_contents($cacheDebugFileName, $content, \LOCK_EX)) {
            throw new Exceptions\CanNotSaveContentForDebug('Can not save content for debugging purpose into ' . $cacheDebugFileName);
        }
        if (!@\chmod($cacheDebugFileName, 0664)) {
            throw new Exceptions\CanNotChangeAccessToFileWithContentForDebug(
                'Can not change access to 0644 for file with content for debug ' . $cacheDebugFileName
            );
        }
    }

    /**
     * @return string
     * @throws \RuntimeException
     */
    private function getCacheDebugFileName(): string
    {
        return $this->getCacheDir() . "/{$this->geCacheDebugFileBaseNamePartWithoutGet()}_{$this->getCurrentRequestHash()}.html";
    }

    private function geCacheDebugFileBaseNamePartWithoutGet(): string
    {
        return 'debug_' . $this->getCurrentContentHash();
    }

    /**
     * @param string $content
     * @throws \RuntimeException
     */
    public function cacheContent(string $content): void
    {
        $cacheFileName = $this->getCacheFileName();
        \file_put_contents($cacheFileName, $content, \LOCK_EX);
        \chmod($cacheFileName, 0664);
        $this->clearOldCache();
    }

    /**
     * @throws \RuntimeException
     */
    private function clearOldCache(): void
    {
        $foldersToSkip = ['.', '..', '.gitignore'];
        $currentVersion = $this->currentWebVersion->getCurrentMinorVersion();
        $cacheRoot = $this->cacheRoots[$currentVersion];

        $currentContentHash = $this->getCurrentContentHash();
        foreach (\scandir($cacheRoot, \SCANDIR_SORT_NONE) as $folder) {
            if (\in_array($folder, $foldersToSkip, true)) {
                continue;
            }
            if (\strpos($folder, $currentContentHash) !== false) { // that file is valid
                continue;
            }
            \unlink($cacheRoot . '/' . $folder);
        }
    }
}