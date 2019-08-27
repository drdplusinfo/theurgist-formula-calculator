<?php declare(strict_types=1);

namespace Granam\Git;

use Granam\Strict\Object\StrictObject;

class Git extends StrictObject
{
    public const INCLUDE_LOCAL_BRANCHES = true;
    public const EXCLUDE_LOCAL_BRANCHES = false;
    public const INCLUDE_REMOTE_BRANCHES = true;
    public const EXCLUDE_REMOTE_BRANCHES = false;

    private $sleepMultiplierOnLock;

    public function __construct(int $sleepMultiplierOnLock = 1)
    {
        $this->sleepMultiplierOnLock = $sleepMultiplierOnLock;
    }

    /**
     * @param string $repositoryDir
     * @return array|string[] Rows with GIT status
     * @throws \Granam\Git\Exceptions\CanNotGetGitStatus
     */
    public function getGitStatus(string $repositoryDir): array
    {
        // GIT status is same for any working dir, if it is a sub-dir of wanted GIT project root
        try {
            $escapedDir = escapeshellarg($repositoryDir);

            return $this->executeArray("git -C $escapedDir status");
        } catch (Exceptions\ExecutingCommandFailed $executingCommandFailed) {
            throw new Exceptions\CanNotGetGitStatus(
                "Can not get git status from dir $repositoryDir:\n"
                . $executingCommandFailed->getMessage(),
                $executingCommandFailed->getCode(),
                $executingCommandFailed
            );
        }
    }

    /**
     * @param string $repositoryDir
     * @return array|string[] Rows with differences
     * @throws \Granam\Git\Exceptions\CanNotGetGitDiff
     */
    public function getDiffAgainstOrigin(string $repositoryDir): array
    {
        try {
            $escapedDir = escapeshellarg($repositoryDir);
            $escapedCurrentBranchName = escapeshellarg($this->getCurrentBranchName($repositoryDir));

            return $this->executeArray("git -C $escapedDir diff origin/$escapedCurrentBranchName");
        } catch (Exceptions\ExecutingCommandFailed $executingCommandFailed) {
            throw new Exceptions\CanNotGetGitDiff(
                "Can not get diff:\n"
                . $executingCommandFailed->getMessage(),
                $executingCommandFailed->getCode(),
                $executingCommandFailed
            );
        }
    }

    /**
     * @param string $repositoryDir
     * @return string Last commit hash
     * @throws \Granam\Git\Exceptions\ExecutingCommandFailed
     */
    public function getLastCommitHash(string $repositoryDir): string
    {
        $escapedDir = escapeshellarg($repositoryDir);

        return $this->execute("git -C $escapedDir log --max-count=1 --format=%H --no-abbrev-commit");
    }

    /**
     * @param string $branch
     * @param string $destinationDir
     * @param string $repositoryUrl
     * @return array|string[] Rows with result of branch clone
     * @throws \Granam\Git\Exceptions\CanNotLocallyCloneWebVersionViaGit
     * @throws \Granam\Git\Exceptions\UnknownMinorVersion
     */
    public function cloneBranch(string $branch, string $repositoryUrl, string $destinationDir): array
    {
        $destinationDirEscaped = escapeshellarg($destinationDir);
        $branchEscaped = escapeshellarg($branch);
        try {
            return $this->executeArray("git clone --branch $branchEscaped $repositoryUrl $destinationDirEscaped");
        } catch (Exceptions\ExecutingCommandFailed $executingCommandFailed) {
            if ($this->remoteBranchExists($branch)) {
                throw new Exceptions\CanNotLocallyCloneWebVersionViaGit(
                    "Can not git clone required version '{$branch}':\n"
                    . $executingCommandFailed->getMessage(),
                    $executingCommandFailed->getCode(),
                    $executingCommandFailed
                );
            }
            throw new Exceptions\UnknownMinorVersion(
                "Required minor version $branch as a GIT branch does not exists:\n"
                . $executingCommandFailed->getMessage(),
                $executingCommandFailed->getCode(),
                $executingCommandFailed
            );
        }
    }

    /**
     * @param string $branch
     * @param string $repositoryDir
     * @return array|string[] Rows with result of branch update
     * @throws \Granam\Git\Exceptions\CanNotLocallyCloneWebVersionViaGit
     * @throws \Granam\Git\Exceptions\UnknownMinorVersion
     */
    public function updateBranch(string $branch, string $repositoryDir): array
    {
        $branchEscaped = escapeshellarg($branch);
        $repositoryDirEscaped = escapeshellarg($repositoryDir);
        $commands = [];
        $commands[] = "cd $repositoryDirEscaped";
        $commands[] = "git checkout $branchEscaped";
        $commands[] = 'git pull --ff-only';
        $commands[] = 'git pull --tags';
        $commands[] = 'git checkout -';

        return $this->executeCommandsChainArray($commands);
    }

    /**
     * @param string $repositoryDir
     * @param int $maxAttempts
     * @return array|string[] Rows with result of branch update
     * @throws \Granam\Git\Exceptions\CanNotLocallyCloneWebVersionViaGit
     * @throws \Granam\Git\Exceptions\UnknownMinorVersion
     */
    public function update(string $repositoryDir, int $maxAttempts = 3): array
    {
        $repositoryDirEscaped = escapeshellarg($repositoryDir);
        $attempt = 1;
        $commands = [
            "echo 'attempt number $attempt'",
            "cd $repositoryDirEscaped",
            'git pull --ff-only',
            'git pull --tags',
        ];
        do {
            try {
                return $this->executeCommandsChainArray($commands);
            } catch (Exceptions\ExecutingCommandFailed $executingCommandFailed) {
                if (!preg_match("~Unable to create '[^']+[.]lock': File exists[.]~", $executingCommandFailed->getMessage())) {
                    throw $executingCommandFailed;
                }
                if ($attempt === $maxAttempts) {
                    throw $executingCommandFailed;
                }
                sleep($this->sleepMultiplierOnLock * $attempt); // like 1 s, 2 s, ...
                $attempt++;
                $commands[0] = "echo 'attempt number $attempt'";
                continue;
            }
        } while ($attempt <= $maxAttempts);
    }

    /**
     * @param string $branchName
     * @return bool
     * @throws \Granam\Git\Exceptions\CanNotFindOutRemoteBranches
     */
    public function remoteBranchExists(string $branchName): bool
    {
        try {
            $rows = $this->executeArray('git branch --remotes');
        } catch (Exceptions\ExecutingCommandFailed $executingCommandFailed) {
            throw new Exceptions\CanNotFindOutRemoteBranches(
                $executingCommandFailed->getMessage(),
                $executingCommandFailed->getCode(),
                $executingCommandFailed
            );
        }
        foreach ($rows as $remoteBranch) {
            $branchFromRemote = trim(explode('/', $remoteBranch)[1] ?? '');
            if ($branchName === $branchFromRemote) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $repositoryDir
     * @return array|string[] List of tags with patch versions like 1.12.321
     * @throws \Granam\Git\Exceptions\ExecutingCommandFailed
     */
    public function getAllPatchVersions(string $repositoryDir): array
    {
        $repositoryDirEscaped = escapeshellarg($repositoryDir);
        $commands = [
            "git -C $repositoryDirEscaped tag",
            'grep -E "v?([[:digit:]]+[.]){2}[[:alnum:]]+([.][[:digit:]]+)?" --only-matching',
            'sort --version-sort --reverse',
        ];

        return $this->executePipedArray($commands);
    }

    private function executePipedArray(array $commands)
    {
        foreach ($commands as &$command) {
            $command .= ' 2>&1';
        }

        return $this->executeArray(implode(' | ', $commands), false);
    }

    /**
     * @param string $repositoryDir
     * @param bool $readLocal
     * @param bool $readRemote
     * @return array|string[] List of branches with minor versions like 1.13, 1.12, sorted from newest to oldest
     * @throws \Granam\Git\Exceptions\LocalOrRemoteBranchesShouldBeRequired
     * @throws \Granam\Git\Exceptions\ExecutingCommandFailed
     */
    public function getAllMinorVersions(
        string $repositoryDir,
        bool $readLocal = self::INCLUDE_LOCAL_BRANCHES,
        bool $readRemote = self::INCLUDE_REMOTE_BRANCHES
    ): array
    {
        if (!$readLocal && !$readRemote) {
            throw new Exceptions\LocalOrRemoteBranchesShouldBeRequired(
                'Excluding both local and remote version-like branches has no sense'
            );
        }
        $repositoryDirEscaped = escapeshellarg($repositoryDir);
        $branchesCommandParts = [];
        if ($readLocal) {
            $branchesCommandParts[] = "git -C $repositoryDirEscaped branch 2>&1";
        }
        if ($readRemote) {
            $branchesCommandParts[] = "git -C $repositoryDirEscaped branch -r 2>&1";
        }
        $branches = $this->executeCommandsChainArray($branchesCommandParts);
        $escapedBranches = escapeshellarg(implode("\n", $branches));
        $commands = [
            $escapedBranches,
            'cut -d "/" -f2',
            'grep HEAD --invert-match',
            'grep -P "v?\d+\.\d+" --only-matching',
            'uniq',
            'sort --version-sort --reverse',
        ];

        return $this->executePipedArray($commands);
    }

    public function getLastStableMinorVersion(
        string $repositoryDir,
        bool $readLocal = self::INCLUDE_LOCAL_BRANCHES,
        bool $readRemote = self::INCLUDE_REMOTE_BRANCHES
    ): ?string
    {
        return $this->getAllMinorVersions($repositoryDir, $readLocal, $readRemote)[0] ?? null;
    }

    /**
     * @param string $superiorVersion
     * @param string $repositoryDir
     * @return string
     * @throws \Granam\Git\Exceptions\NoPatchVersionsMatch
     * @throws \Granam\Git\Exceptions\ExecutingCommandFailed
     */
    public function getLastPatchVersionOf(string $superiorVersion, string $repositoryDir): string
    {
        $patchVersions = $this->getAllPatchVersions($repositoryDir);
        $matchingPatchVersions = [];
        foreach ($patchVersions as $patchVersion) {
            if (strpos($patchVersion, $superiorVersion) === 0) {
                $matchingPatchVersions[] = $patchVersion;
            }
        }
        if (!$matchingPatchVersions) {
            throw new Exceptions\NoPatchVersionsMatch(
                sprintf(
                    'No patch version matches given superior version %s, %s',
                    $superiorVersion,
                    $patchVersions
                        ? 'available are only' . implode(',', $patchVersions)
                        : 'because there are no patch versions at all'
                )
            );
        }
        usort($matchingPatchVersions, 'version_compare');

        return end($matchingPatchVersions);
    }

    /**
     * @param string $repositoryDir
     * @return string|null
     * @throws \Granam\Git\Exceptions\ExecutingCommandFailed
     */
    public function getLastPatchVersion(string $repositoryDir): ?string
    {
        $lastStableMinorVersion = $this->getLastStableMinorVersion($repositoryDir);
        if ($lastStableMinorVersion === null) {
            return null;
        }
        try {
            return $this->getLastPatchVersionOf($lastStableMinorVersion, $repositoryDir);
        } catch (Exceptions\NoPatchVersionsMatch $noPatchVersionsMatch) {
            return null;
        }
    }

    /**
     * @param string $command
     * @param bool $sendErrorsToStdOut = true
     * @param bool $solveMissingHomeDir = true
     * @return string[]|array
     * @throws \Granam\Git\Exceptions\ExecutingCommandFailed
     */
    private function executeArray(string $command, bool $sendErrorsToStdOut = true, bool $solveMissingHomeDir = true): array
    {
        if ($sendErrorsToStdOut) {
            $command .= ' 2>&1';
        }
        if ($solveMissingHomeDir) {
            $homeDir = exec('echo $HOME 2>&1', $output, $returnCode);
            $this->guardCommandWithoutError($returnCode, $command, $output);
            if (!$homeDir) {
                if (file_exists('/home/www-data')) {
                    $command = 'export HOME=/home/www-data 2>&1 && ' . $command;
                } elseif (file_exists('/var/www')) {
                    $command = 'export HOME=/var/www 2>&1 && ' . $command;
                } // else we will hope it will somehow pass without fatal: failed to expand user dir in: '~/.gitignore'
            }
        }
        $returnCode = 0;
        $output = [];
        exec($command, $output, $returnCode);
        $this->guardCommandWithoutError($returnCode, $command, $output);

        return $output;
    }

    /**
     * @param int $returnCode
     * @param string $command
     * @param array $output
     * @throws \Granam\Git\Exceptions\ExecutingCommandFailed
     */
    private function guardCommandWithoutError(int $returnCode, string $command, ?array $output): void
    {
        if ($returnCode !== 0) {
            throw new Exceptions\ExecutingCommandFailed(
                "Error while executing '$command', expected return '0', got '$returnCode'"
                . ($output !== null ?
                    (" with output: '" . implode("\n", $output) . "'")
                    : ''
                ),
                $returnCode
            );
        }
    }

    /**
     * @param string $command
     * @param bool $sendErrorsToStdOut = true
     * @param bool $solveMissingHomeDir = true
     * @return string
     * @throws \Granam\Git\Exceptions\ExecutingCommandFailed
     */
    private function execute(string $command, bool $sendErrorsToStdOut = true, bool $solveMissingHomeDir = true): string
    {
        $rows = $this->executeArray($command, $sendErrorsToStdOut, $solveMissingHomeDir);
        if (!$rows) {
            return '';
        }
        return end($rows);
    }

    /**
     * @param array $commands
     * @return array|string[]
     * @throws \Granam\Git\Exceptions\ExecutingCommandFailed
     */
    private function executeCommandsChainArray(array $commands): array
    {
        return $this->executeArray($this->getChainedCommands($commands), false);
    }

    /**
     * @param array $commands
     * @return string
     */
    private function getChainedCommands(array $commands): string
    {
        foreach ($commands as &$command) {
            $command .= ' 2>&1';
        }

        return implode(' && ', $commands);
    }

    public function getCurrentBranchName(string $repositoryDir): string
    {
        $escapedRepositoryDir = escapeshellarg($repositoryDir);
        $branchName = $this->executePiped([
            "git -C $escapedRepositoryDir branch",
            'grep "*"',
            'cut -d "*" -f2',
        ]);

        return trim($branchName);
    }

    private function executePiped(array $commands): string
    {
        foreach ($commands as &$command) {
            $command .= ' 2>&1';
        }

        return $this->execute(implode(' | ', $commands), false);
    }

}