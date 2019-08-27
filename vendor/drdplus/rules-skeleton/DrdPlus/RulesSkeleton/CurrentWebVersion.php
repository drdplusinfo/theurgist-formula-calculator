<?php declare(strict_types=1);

namespace DrdPlus\RulesSkeleton;

use DrdPlus\WebVersions\WebVersions;
use Granam\Git\Git;
use Granam\Strict\Object\StrictObject;

/**
 * Reader of GIT tags defining available versions of web filesF
 */
class CurrentWebVersion extends StrictObject
{

    /** @var Dirs */
    private $dirs;
    /** @var string */
    private $currentCommitHash;
    /** @var string */
    private $currentPatchVersion;
    /** @var Git */
    private $git;
    /** @var WebVersions */
    private $webVersions;

    public function __construct(Dirs $dirs, Git $git, WebVersions $webVersions)
    {
        $this->dirs = $dirs;
        $this->git = $git;
        $this->webVersions = $webVersions;
    }

    public function getCurrentMinorVersion(): string
    {
        return $this->git->getCurrentBranchName($this->dirs->getProjectRoot());
    }

    public function getCurrentPatchVersion(): string
    {
        if ($this->currentPatchVersion === null) {
            $this->currentPatchVersion = $this->webVersions->getLastPatchVersionOf($this->getCurrentMinorVersion());
        }

        return $this->currentPatchVersion;
    }

    public function getCurrentCommitHash(): string
    {
        if ($this->currentCommitHash === null) {
            $this->currentCommitHash = $this->git->getLastCommitHash($this->dirs->getProjectRoot());
        }

        return $this->currentCommitHash;
    }
}