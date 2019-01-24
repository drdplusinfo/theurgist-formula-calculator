<?php
declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;
use DrdPlus\WebVersions\WebVersions;
use Granam\Git\Git;
use PHPUnit\Framework\TestCase;

class CurrentWebVersionTest extends AbstractContentTest
{

    /**
     * @test
     */
    public function I_can_get_current_version(): void
    {
        $git = $this->createGitWithCurrentBranchName('foo');
        self::assertSame(
            'foo',
            $this->createCurrentWebVersion(null, $git)->getCurrentMinorVersion()
        );
    }

    private function createGitWithCurrentBranchName(string $branchName)
    {
        return new class($branchName, $this->getProjectRoot()) extends Git
        {
            private $branchName;
            private $expectedRepositoryDir;

            public function __construct(string $branchName, string $expectedRepositoryDir)
            {
                $this->branchName = $branchName;
                $this->expectedRepositoryDir = $expectedRepositoryDir;
            }

            public function getCurrentBranchName(string $repositoryDir): string
            {
                TestCase::assertSame($this->expectedRepositoryDir, $repositoryDir);

                return $this->branchName;
            }

        };
    }

    /**
     * @test
     */
    public function I_can_get_current_patch_version(): void
    {
        $webVersions = $this->createWebVersions();
        $currentWebVersion = $this->createCurrentWebVersion();
        if ($currentWebVersion->getCurrentMinorVersion() === $webVersions->getLastUnstableVersion()) {
            self::assertSame(
                $webVersions->getLastUnstableVersion(),
                $currentWebVersion->getCurrentPatchVersion()
            );
        } else {
            self::assertRegExp(
                '~^' . \preg_quote($currentWebVersion->getCurrentMinorVersion(), '~') . '[.]\d+$~',
                $currentWebVersion->getCurrentPatchVersion()
            );
        }
    }

    /**
     * @test
     */
    public function I_will_get_unstable_version_if_there_is_no_last_stable_version(): void
    {
        $gitWithoutLastStableVersion = $this->createGitWithoutLastStableVersion($this->getProjectRoot());
        $webVersions = $this->createWebVersions($gitWithoutLastStableVersion, $this->getProjectRoot());
        self::assertSame(WebVersions::LAST_UNSTABLE_VERSION, $webVersions->getLastUnstableVersion());
        self::assertSame($webVersions->getLastUnstableVersion(), $webVersions->getLastStableMinorVersion());
    }

    private function createGitWithoutLastStableVersion(string $expectedRepositoryDir): Git
    {
        return new class($expectedRepositoryDir) extends Git
        {
            private $expectedRepositoryDir;

            public function __construct(string $expectedRepositoryDir)
            {
                $this->expectedRepositoryDir = $expectedRepositoryDir;
            }

            public function getLastStableMinorVersion(
                string $dir,
                bool $readLocal = self::INCLUDE_LOCAL_BRANCHES,
                bool $readRemote = self::INCLUDE_REMOTE_BRANCHES
            ): ?string
            {
                TestCase::assertSame($this->expectedRepositoryDir, $dir);

                return null;
            }

        };
    }

    /**
     * @test
     */
    public function I_can_get_current_commit_hash(): void
    {
        $currentWebVersion = $this->createCurrentWebVersion();
        $currentCommitHash = $currentWebVersion->getCurrentCommitHash(); // called before reading .git/HEAD to ensure it exists
        $lastCommitHashFromGitHeadFile = $this->getLastCommitHashFromGitHeadFile($this->getProjectRoot());
        self::assertSame(
            $lastCommitHashFromGitHeadFile,
            $currentCommitHash,
            \sprintf(
                'Expected different last commit for version %s taken from dir %s',
                $currentWebVersion->getCurrentMinorVersion(),
                $this->getProjectRoot()
            )
        );
    }

    /**
     * @param string $dir
     * @return string
     * @throws \DrdPlus\Tests\RulesSkeleton\Exceptions\CanNotReadGitHead
     */
    private function getLastCommitHashFromGitHeadFile(string $dir): string
    {
        $head = \file_get_contents($dir . '/.git/HEAD');
        if (\preg_match('~^[[:alnum:]]{40,}$~', $head)) {
            return $head; // the HEAD file contained the has itself
        }
        $gitHeadFile = \trim(\preg_replace('~ref:\s*~', '', \file_get_contents($dir . '/.git/HEAD')));
        $gitHeadFilePath = $dir . '/.git/' . $gitHeadFile;
        if (!\is_readable($gitHeadFilePath)) {
            throw new Exceptions\CanNotReadGitHead(
                "Could not read $gitHeadFilePath, in that dir are files "
                . \implode(',', \scandir(\dirname($gitHeadFilePath), SCANDIR_SORT_NONE))
            );
        }

        return \trim(\file_get_contents($gitHeadFilePath));
    }

    /**
     * @test
     */
    public function I_will_get_last_unstable_version_as_patch_version(): void
    {
        $webVersions = $this->createWebVersions();
        self::assertSame($webVersions->getLastUnstableVersion(), $webVersions->getLastPatchVersionOf($webVersions->getLastUnstableVersion()));
    }

    /**
     * @test
     * @expectedException \Granam\Git\Exceptions\NoPatchVersionsMatch
     */
    public function I_can_not_get_last_patch_version_for_non_existing_version(): void
    {
        $nonExistingVersion = '-999.999';
        $webVersions = $this->createWebVersions();
        try {
            self::assertNotContains($nonExistingVersion, $webVersions->getAllMinorVersions(), 'This version really exists?');
        } catch (\Exception $exception) {
            self::fail('No exception expected so far: ' . $exception->getMessage());
        }
        $webVersions->getLastPatchVersionOf($nonExistingVersion);
    }

    /**
     * @test
     */
    public function I_can_get_current_minor_version(): void
    {
        $branchName = 'foo.bar';
        $git = $this->createGitWithCurrentBranchName($branchName);
        /** @var WebVersions $webVersions */
        $currentWebVersion = $this->createCurrentWebVersion(null, $git);

        self::assertSame($branchName, $currentWebVersion->getCurrentMinorVersion());
    }
}