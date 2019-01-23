<?php
declare(strict_types=1);

namespace DrdPlus\WebVersions;

use Granam\Git\Git;
use Granam\Strict\Object\StrictObject;

/**
 * Reader of GIT tags defining available versions of web filesF
 */
class WebVersions extends StrictObject
{

    public const LAST_UNSTABLE_VERSION = 'master';

    /** @var string[] */
    private $allMinorVersions;
    /** @var string */
    private $lastStableMinorVersion;
    /** @var string */
    private $lastStablePatchVersion;
    /** @var string[] */
    private $allStableVersions;
    /** @var string[] */
    private $lastPatchVersionsOf = [];
    /** @var null|string[] */
    private $patchVersions;
    /** @var Git */
    private $git;
    /** @var string */
    private $repositoryDir;
    /** @var string */
    private $lastUnstableVersion;

    public function __construct(Git $git, string $repositoryDir, string $lastUnstableVersion = self::LAST_UNSTABLE_VERSION)
    {
        $this->git = $git;
        $this->repositoryDir = $repositoryDir;
        $this->lastUnstableVersion = $lastUnstableVersion;
    }

    /**
     * Intentionally are versions taken from branches only, not tags, to lower amount of versions to switch into.
     * @return array|string[] Includes last unstable version, probably "master"
     */
    public function getAllMinorVersions(): array
    {
        if ($this->allMinorVersions === null) {
            $allMinorVersionLikeBranches = $this->git->getAllMinorVersions($this->repositoryDir);
            \array_unshift($allMinorVersionLikeBranches, $this->getLastUnstableVersion());
            $this->allMinorVersions = $allMinorVersionLikeBranches;
        }

        return $this->allMinorVersions;
    }

    /**
     * @return string Last stable minor version, if any, or 'master' if none
     */
    public function getLastStableMinorVersion(): string
    {
        if ($this->lastStableMinorVersion === null) {
            $this->lastStableMinorVersion = $this->git->getLastStableMinorVersion($this->repositoryDir)
                ?? $this->getLastUnstableVersion();
        }

        return $this->lastStableMinorVersion;
    }

    /**
     * @return string Last stable patch version, if any, or 'master' if no stable version is available
     */
    public function getLastStablePatchVersion(): string
    {
        if ($this->lastStablePatchVersion === null) {
            $this->lastStablePatchVersion = $this->git->getLastPatchVersion($this->repositoryDir)
                ?? $this->getLastUnstableVersion();
        }

        return $this->lastStablePatchVersion;
    }

    public function getLastUnstableVersion(): string
    {
        return $this->lastUnstableVersion;
    }

    public function getAllStableMinorVersions(): array
    {
        if ($this->allStableVersions === null) {
            $this->allStableVersions = $this->git->getAllMinorVersions($this->repositoryDir);
        }

        return $this->allStableVersions;
    }

    public function hasMinorVersion(string $minorVersion): bool
    {
        return \in_array($minorVersion, $this->getAllMinorVersions(), true);
    }

    public function getVersionHumanName(string $version): string
    {
        return $version !== $this->getLastUnstableVersion() ? "verze $version" : 'testovací!';
    }

    public function getLastPatchVersionOf(string $superiorVersion): string
    {
        if (($this->lastPatchVersionsOf[$superiorVersion] ?? null) === null) {
            if ($superiorVersion === $this->getLastUnstableVersion()) {
                $this->lastPatchVersionsOf[$superiorVersion] = $this->getLastUnstableVersion();
            } else {
                $this->lastPatchVersionsOf[$superiorVersion] = $this->git->getLastPatchVersionOf($superiorVersion, $this->repositoryDir);
            }
        }

        return $this->lastPatchVersionsOf[$superiorVersion];
    }

    /**
     * @return array|string[]
     */
    public function getAllPatchVersions(): array
    {
        if ($this->patchVersions === null) {
            $this->patchVersions = $this->git->getAllPatchVersions($this->repositoryDir);
        }

        return $this->patchVersions;
    }
}