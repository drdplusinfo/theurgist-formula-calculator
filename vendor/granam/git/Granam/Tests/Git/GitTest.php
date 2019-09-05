<?php declare(strict_types=1);

namespace Granam\Tests\Git;

use Granam\Git\Exceptions\ExecutingCommandFailed;
use Granam\Git\Git;
use PHPUnit\Framework\TestCase;

class GitTest extends TestCase
{
    /**
     * @test
     */
    public function I_can_get_git_status(): void
    {
        self::assertNotEmpty($this->getGit()->getGitStatus(__DIR__), 'Expected some GIT status');
    }

    private function getGit(): Git
    {
        return new Git();
    }

    /**
     * @test
     */
    public function I_can_get_diff_against_origin(): void
    {
        self::assertIsArray($this->getGit()->getDiffAgainstOrigin(__DIR__));
    }

    /**
     * @test
     */
    public function I_can_get_last_commit(): void
    {
        $expectedLastCommit = trim(file_get_contents(__DIR__ . '/../../../.git/refs/heads/master'));
        self::assertSame($expectedLastCommit, $this->getGit()->getLastCommitHash(__DIR__));
    }

    /**
     * @test
     */
    public function I_can_ask_if_remote_branch_exists(): void
    {
        self::assertTrue(
            $this->getGit()->remoteBranchExists('master'),
            'Expected master branch to be detected as existing in remote repository'
        );
        self::assertFalse(
            $this->getGit()->remoteBranchExists('nonsense'),
            "'nonsense' branch is not expected to exists at all"
        );
    }

    /**
     * @test
     */
    public function I_can_get_all_patch_versions(): void
    {
        self::assertNotEmpty($this->getGit()->getAllPatchVersions(__DIR__));
    }

    /**
     * @test
     * @dataProvider provideBranchesSourceFlags
     * @param bool $includeLocalBranches
     * @param bool $includeRemoteBranches
     */
    public function I_can_get_all_minor_versions(bool $includeLocalBranches, bool $includeRemoteBranches): void
    {
        self::assertContains(
            '1.0',
            $this->getGit()->getAllMinorVersions(__DIR__, $includeLocalBranches, $includeRemoteBranches)
        );
    }

    public function provideBranchesSourceFlags(): array
    {
        return [
            'both local and remote branches' => [Git::INCLUDE_LOCAL_BRANCHES, Git::INCLUDE_REMOTE_BRANCHES],
            'only local branches' => [Git::INCLUDE_LOCAL_BRANCHES, Git::EXCLUDE_REMOTE_BRANCHES],
            'only remote branches' => [Git::EXCLUDE_LOCAL_BRANCHES, Git::INCLUDE_REMOTE_BRANCHES],
        ];
    }

    /**
     * @test
     */
    public function I_can_not_exclude_both_local_and_remote_branches_when_asking_to_versions(): void
    {
        $this->expectException(\Granam\Git\Exceptions\LocalOrRemoteBranchesShouldBeRequired::class);
        $this->getGit()->getAllMinorVersions(__DIR__, Git::EXCLUDE_LOCAL_BRANCHES, Git::EXCLUDE_REMOTE_BRANCHES);
    }

    /**
     * @test
     */
    public function I_can_get_last_stable_minor_version(): void
    {
        self::assertRegExp(
            '~^v?\d+[.]\d+$~',
            $this->getGit()->getLastStableMinorVersion(__DIR__),
            'Some last stable minor version expected'
        );
    }

    /**
     * @test
     */
    public function I_can_get_last_patch_version_of_minor_version(): void
    {
        self::assertRegExp(
            '~^1[.]0[.]\d+$~',
            $this->getGit()->getLastPatchVersionOf('1.0', __DIR__),
            'Some last patch version to a minor version expected'
        );
    }

    /**
     * @test
     */
    public function I_am_stopped_when_asking_for_last_patch_version_of_non_existing_minor_version(): void
    {
        $this->expectExceptionMessageRegExp('~999[.]999~');
        $this->expectException(\Granam\Git\Exceptions\NoPatchVersionsMatch::class);
        $this->getGit()->getLastPatchVersionOf('999.999', __DIR__);
    }

    /**
     * @test
     */
    public function I_can_get_last_patch_version(): void
    {
        self::assertRegExp(
            '~^v?(\d+[.]){2}\d+$~',
            $this->getGit()->getLastPatchVersion(__DIR__),
            'Some last patch version expected'
        );
    }

    /**
     * @test
     */
    public function I_can_get_current_branch_name(): void
    {
        self::assertRegExp('~^(master|v?\d+[.]\d+)$~', $this->getGit()->getCurrentBranchName(__DIR__));
    }

    /**
     * @test
     */
    public function It_can_detect_lock()
    {
        $tempDir = sys_get_temp_dir();
        $tempGitDir = $tempDir . '/' . uniqid('git_lock_test', true);
        $tempGitOriginDir = $tempDir . '/' . uniqid('git_origin_lock_test', true);
        $tempGitOriginDirEscaped = escapeshellarg($tempGitOriginDir);
        $tempGitDirEscaped = escapeshellarg($tempGitDir);
        $commands = [
            "mkdir $tempGitDirEscaped",
            "cd $tempGitDirEscaped",
            "git init",
            "touch foo",
            "git add foo",
            "git commit -m 'bar'",
            "cp -r $tempGitDirEscaped $tempGitOriginDirEscaped",
            "git remote add origin $tempGitOriginDirEscaped",
            "git fetch",
            "git branch --set-upstream-to=origin/master master",
            "cd $tempGitOriginDirEscaped",
            "touch baz",
            "git add baz",
            "git commit -m 'qux'",
        ];
        $command = implode(' 2>&1 && ', $commands) . ' 2>&1';
        exec(
            $command,
            $output,
            $returnCode
        );
        self::assertSame(0, $returnCode, "Failed command $command: " . implode("\n", $output));

        $commands = [
            "touch $tempGitDirEscaped/.git/refs/remotes/origin/master.lock",
        ];
        $command = implode(' 2>&1 && ', $commands) . ' 2>&1';
        exec(
            $command,
            $output,
            $returnCode
        );
        self::assertSame(0, $returnCode, "Failed command $command: " . implode("\n", $output));

        $git = new Git(0 /* instant "sleep" */);
        try {
            $git->update($tempGitDir);
        } catch (ExecutingCommandFailed $executingCommandFailed) {
            unlink("$tempGitDir/.git/refs/remotes/origin/master.lock");
            $git->update($tempGitDir);
        }
    }

    /**
     * @test
     * @dataProvider provideRestorableError
     * @param string $errorMessage
     */
    public function It_will_try_to_restore_from_restorable_errors(string $errorMessage)
    {
        $maxAttempts = 5;
        $git = new class($errorMessage, $maxAttempts) extends Git
        {
            private $errorMessage;
            private $throwOnAttemptsLesserThan;
            private $attempt = 0;

            public function __construct(string $errorMessage, int $throwOnAttemptsLesserThan)
            {
                parent::__construct(0 /* no sleep */);
                $this->errorMessage = $errorMessage;
                $this->throwOnAttemptsLesserThan = $throwOnAttemptsLesserThan;
            }

            protected function executeArray(string $command, bool $sendErrorsToStdOut = true, bool $solveMissingHomeDir = true): array
            {
                $this->attempt++;
                if ($this->attempt < $this->throwOnAttemptsLesserThan) {
                    throw new ExecutingCommandFailed($this->errorMessage);
                }
                return [$command];
            }
        };
        $output = $git->update('foo', $maxAttempts);
        self::assertStringContainsString("attempt number $maxAttempts", $output[0]);
    }

    public function provideRestorableError()
    {
        return [
            [<<<TEXT
From gitlab.com:drdplusinfo/bestiar
   7e73a42..606b6c3  1.0        -> origin/1.0
   7e73a42..606b6c3  master     -> origin/master
 * [new tag]         1.0.11     -> 1.0.11
Updating 7e73a42..606b6c3
Fast-forward
 composer.json                                      |  6 --
 composer.lock                                      | 26 +++---
 .../generic/skeleton/rules-images.css              |  0
 css/generic/skeleton/rules-main.css                | 18 ++++-
 vendor/composer/installed.json                     | 28 ++++---
 .../DrdPlus/RulesSkeleton/HomepageDetector.php     | 10 ++-
 .../DrdPlus/RulesSkeleton/RulesUrlMatcher.php      |  1 +
 .../DrdPlus/RulesSkeleton/Web/content/menu.php     |  4 +-
 .../DrdPlus/Tests/RulesSkeleton/AnchorsTest.php    | 80 ++++++------------
 .../Tests/RulesSkeleton/CalculationsTest.php       | 94 ++++++++++++++++++----
 .../Tests/RulesSkeleton/ComposerConfigTest.php     | 35 +++++++-
 .../Tests/RulesSkeleton/CurrentWebVersionTest.php  | 10 ++-
 .../Tests/RulesSkeleton/HomepageDetectorTest.php   | 34 ++++++++
 .../DrdPlus/Tests/RulesSkeleton/HtmlHelperTest.php | 15 +++-
 .../Partials/TestsConfigurationReader.php          |  2 +
 .../DrdPlus/Tests/RulesSkeleton/PassingTest.php    |  9 ++-
 .../Tests/RulesSkeleton/StandardModeTest.php       | 12 ++-
 .../Tests/RulesSkeleton/TableOfContentsTest.php    | 12 ++-
 .../Tests/RulesSkeleton/TestsConfiguration.php     | 14 ++++
 .../Tests/RulesSkeleton/TestsConfigurationTest.php |  3 +-
 .../DrdPlus/Tests/RulesSkeleton/Web/MenuTest.php   | 39 +++++++++
 .../RulesSkeleton/Web/RulesMainContentTest.php     | 40 +++++++--
 .../css/generic/skeleton/rules-images.css          |  0
 .../css/generic/skeleton/rules-main.css            | 18 ++++-
 vendor/drdplus/rules-skeleton/web/04 headings.html |  7 +-
 .../drdplus/rules-skeleton/web/24 calculation.html | 31 ++++++-
 .../Tests/WebContentBuilder/HtmlHelperTest.php     | 73 ++++++++++++++++-
 .../Granam/WebContentBuilder/Dirs.php              | 13 +--
 .../Granam/WebContentBuilder/HtmlHelper.php        | 70 ++++++++++++----
 29 files changed, 559 insertions(+), 145 deletions(-)
 rename vendor/drdplus/rules-skeleton/css/generic/skeleton/rulesimages.css => css/generic/skeleton/rules-images.css (100%)
 rename css/generic/skeleton/rulesimages.css => vendor/drdplus/rules-skeleton/css/generic/skeleton/rules-images.css (100%)
It doesn't make sense to pull all tags; you probably meant:
  git fetch --tags
TEXT
                ,
                <<<TEXT
attempt number 1
error: Ref refs/remotes/origin/1.0 is at 606b6c32a03cfd02982dd55b413a2cf01f53a175 but expected 7e73a42f1593894e5ebb0f636010df2461dc9ce6
From gitlab.com:drdplusinfo/bestiar
 ! 7e73a42..606b6c3  1.0        -> origin/1.0  (unable to update local ref)
error: Ref refs/remotes/origin/master is at 606b6c32a03cfd02982dd55b413a2cf01f53a175 but expected 7e73a42f1593894e5ebb0f636010df2461dc9ce6
 ! 7e73a42..606b6c3  master     -> origin/master  (unable to update local ref)
 * [new tag]         1.0.11     -> 1.0.11
TEXT
                ,
            ],
        ];
    }
}