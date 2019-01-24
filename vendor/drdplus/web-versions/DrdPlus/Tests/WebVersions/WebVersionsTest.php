<?php
declare(strict_types=1);

namespace DrdPlus\Tests\WebVersions;

use DrdPlus\WebVersions\WebVersions;
use Granam\Git\Git;
use PHPUnit\Framework\TestCase;

class WebVersionsTest extends TestCase
{
    /**
     * @test
     */
    public function I_can_get_last_unstable_version(): void
    {
        $webVersions = new WebVersions(new Git(), 'some repository dir');
        self::assertSame('master', $webVersions->getLastUnstableVersion(), 'Expected master as a default unstable version');
        $webVersions = new WebVersions(new Git(), 'some repository dir', 'mistress');
        self::assertSame('mistress', $webVersions->getLastUnstableVersion(), 'Expected given last unstable version to be given back');
    }

    /**
     * @test
     */
    public function I_can_get_all_minor_versions(): void
    {
        $webVersions = new WebVersions(
            $this->createGitWithAllMinorVersions('some repository dir', ['2.0', '1.1', '1.0']),
            'some repository dir',
            'mistress'
        );
        self::assertSame(['mistress', '2.0', '1.1', '1.0'], $webVersions->getAllMinorVersions());
    }

    private function createGitWithAllMinorVersions(string $expectedRepositoryDir, array $mockMinorVersions): Git
    {
        return new class($expectedRepositoryDir, $mockMinorVersions) extends Git
        {
            private $expectedRepositoryDir;
            private $mockVersions;

            public function __construct(string $expectedRepositoryDir, array $mockVersions)
            {
                $this->expectedRepositoryDir = $expectedRepositoryDir;
                $this->mockVersions = $mockVersions;
            }

            public function getAllMinorVersions(string $dir, bool $readLocal = self::INCLUDE_LOCAL_BRANCHES, bool $readRemote = self::INCLUDE_REMOTE_BRANCHES): array
            {
                TestCase::assertTrue(\method_exists(parent::class, __FUNCTION__), parent::class . ' no more has method ' . __FUNCTION__);
                TestCase::assertSame($this->expectedRepositoryDir, $dir);

                return $this->mockVersions;
            }

        };
    }

    /**
     * @test
     * @dataProvider provideLastStableVersion
     * @param string|null $lastStableMinorVersion
     * @param string $lastUnstableVersion
     * @param string $expectedLastStableMinorVersion
     */
    public function I_can_get_last_stable_minor_version(
        ?string $lastStableMinorVersion,
        string $lastUnstableVersion,
        string $expectedLastStableMinorVersion
    ): void
    {
        $webVersions = new WebVersions(
            $this->createGitWithLastStableMinorVersion('some repository dir', $lastStableMinorVersion),
            'some repository dir',
            $lastUnstableVersion
        );
        self::assertSame($expectedLastStableMinorVersion, $webVersions->getLastStableMinorVersion());
    }

    public function provideLastStableVersion(): array
    {
        return [
            'some minor version' => ['2.1', 'mistress', '2.1'],
            'only unstable version' => [null, 'mistress', 'mistress'],
        ];
    }

    private function createGitWithLastStableMinorVersion(string $expectedRepositoryDir, ?string $lastStableVersion): Git
    {
        return new class($expectedRepositoryDir, $lastStableVersion) extends Git
        {
            private $expectedRepositoryDir;
            private $lastStableVersion;

            public function __construct(string $expectedRepositoryDir, ?string $lastStableVersion)
            {
                $this->expectedRepositoryDir = $expectedRepositoryDir;
                $this->lastStableVersion = $lastStableVersion;
            }

            public function getLastStableMinorVersion(string $dir, bool $readLocal = self::INCLUDE_LOCAL_BRANCHES, bool $readRemote = self::INCLUDE_REMOTE_BRANCHES): ?string
            {
                TestCase::assertTrue(\method_exists(parent::class, __FUNCTION__), parent::class . ' no more has method ' . __FUNCTION__);
                TestCase::assertSame($this->expectedRepositoryDir, $dir);

                return $this->lastStableVersion;
            }

        };
    }

    /**
     * @test
     * @dataProvider provideLastStablePatchVersion
     * @param string|null $lastStablePatchVersion
     * @param string $lastUnstableVersion
     * @param string $expectedLastStablePatchVersion
     */
    public function I_can_get_last_stable_patch_version(
        ?string $lastStablePatchVersion,
        string $lastUnstableVersion,
        string $expectedLastStablePatchVersion
    ): void
    {
        $webVersions = new WebVersions(
            $this->createGitWithLastStablePatchVersion('some repository dir', $lastStablePatchVersion),
            'some repository dir',
            $lastUnstableVersion
        );
        self::assertSame($expectedLastStablePatchVersion, $webVersions->getLastStablePatchVersion());
    }

    public function provideLastStablePatchVersion(): array
    {
        return [
            'some patch version' => ['2.1.1', 'mistress', '2.1.1'],
            'only unstable version' => ['mistress', 'mistress', 'mistress'],
        ];
    }

    private function createGitWithLastStablePatchVersion(string $expectedRepositoryDir, string $lastPatchVersion): Git
    {
        return new class($expectedRepositoryDir, $lastPatchVersion) extends Git
        {
            private $expectedRepositoryDir;
            private $lastPatchVersion;

            public function __construct(string $expectedRepositoryDir, ?string $lastPatchVersion)
            {
                $this->expectedRepositoryDir = $expectedRepositoryDir;
                $this->lastPatchVersion = $lastPatchVersion;
            }

            public function getLastPatchVersion(string $dir): string
            {
                TestCase::assertTrue(\method_exists(parent::class, __FUNCTION__), parent::class . ' no more has method ' . __FUNCTION__);
                TestCase::assertSame($this->expectedRepositoryDir, $dir);

                return $this->lastPatchVersion;
            }

        };
    }

    /**
     * @test
     */
    public function I_can_get_all_stable_minor_versions(): void
    {
        $webVersions = new WebVersions(
            $this->createGitWithAllMinorVersions('some repository dir', $minorVersions = ['2.0', '1.2', '1.1', '1.0']),
            'some repository dir'
        );
        self::assertSame($minorVersions, $webVersions->getAllStableMinorVersions());
    }

    /**
     * @test
     */
    public function I_can_find_out_if_minor_version_exists(): void
    {
        $webVersions = new WebVersions(
            $this->createGitWithAllMinorVersions('some repository dir', $minorVersions = ['2.0', '1.2', '1.1', '1.0']),
            'some repository dir',
            'mistress'
        );
        foreach ($minorVersions as $minorVersion) {
            self::assertTrue($webVersions->hasMinorVersion($minorVersion));
        }
        self::assertTrue($webVersions->hasMinorVersion('mistress'));
        self::assertFalse($webVersions->hasMinorVersion('nonsense'));
    }

    /**
     * @test
     */
    public function I_can_get_version_human_name(): void
    {
        $web = new WebVersions(new Git(), 'some repository dir', 'mistress');
        self::assertSame('verze 1.0', $web->getVersionHumanName('1.0'));
        self::assertSame('verze the last of first version', $web->getVersionHumanName('the last of first version'));
        self::assertSame('testovací!', $web->getVersionHumanName('mistress'));
    }

    /**
     * @test
     * @dataProvider provideSuperiorAndRelatedPatchVersions
     * @param string $superiorVersion
     * @param string $expectedPatchVersion
     * @param string $lastUnstableVersion
     */
    public function I_can_get_last_patch_version_of_minor_or_major_version(
        string $superiorVersion,
        string $expectedPatchVersion,
        string $lastUnstableVersion = 'mistress'
    ): void
    {
        $web = new WebVersions(
            $this->createGitWithLastPatchVersionOf('some repository dir', $superiorVersion, $expectedPatchVersion),
            'some repository dir',
            $lastUnstableVersion
        );
        self::assertSame($expectedPatchVersion, $web->getLastPatchVersionOf($superiorVersion));
    }

    public function provideSuperiorAndRelatedPatchVersions(): array
    {
        return [
            'last unstable version' => ['mrs. mistress', 'mrs. mistress', 'mrs. mistress'],
            'minor version' => ['1.1', '1.1.14'],
            'major version' => ['2', '2.6.41'],
        ];
    }

    private function createGitWithLastPatchVersionOf(
        string $expectedRepositoryDir,
        string $expectedSuperiorVersion,
        string $lastPatchVersion
    ): Git
    {
        return new class($expectedRepositoryDir, $expectedSuperiorVersion, $lastPatchVersion) extends Git
        {
            private $expectedRepositoryDir;
            private $expectedSuperiorVersion;
            private $lastPatchVersion;

            public function __construct(
                string $expectedRepositoryDir,
                string $expectedSuperiorVersion,
                ?string $lastPatchVersion
            )
            {
                $this->expectedRepositoryDir = $expectedRepositoryDir;
                $this->expectedSuperiorVersion = $expectedSuperiorVersion;
                $this->lastPatchVersion = $lastPatchVersion;
            }

            public function getLastPatchVersionOf(string $superiorVersion, string $dir): string
            {
                TestCase::assertTrue(\method_exists(parent::class, __FUNCTION__), parent::class . ' no more has method ' . __FUNCTION__);
                TestCase::assertSame($this->expectedSuperiorVersion, $superiorVersion);
                TestCase::assertSame($this->expectedRepositoryDir, $dir);

                return $this->lastPatchVersion;
            }

        };
    }

    /**
     * @test
     */
    public function I_can_get_all_patch_versions(): void
    {
        $webVersions = new WebVersions(
            $this->createGitWithAllPatchVersions('some repository dir', $patchVersions = ['2.0.5', '1.1.0', '1.0.976']),
            'some repository dir',
            'mistress'
        );
        self::assertSame($patchVersions, $webVersions->getAllPatchVersions());
    }

    private function createGitWithAllPatchVersions(string $expectedRepositoryDir, array $mockPatchVersions): Git
    {
        return new class($expectedRepositoryDir, $mockPatchVersions) extends Git
        {
            private $expectedRepositoryDir;
            private $mockPatchVersions;

            public function __construct(string $expectedRepositoryDir, array $mockPatchVersions)
            {
                $this->expectedRepositoryDir = $expectedRepositoryDir;
                $this->mockPatchVersions = $mockPatchVersions;
            }

            public function getAllPatchVersions(string $dir): array
            {
                TestCase::assertTrue(\method_exists(parent::class, __FUNCTION__), parent::class . ' no more has method ' . __FUNCTION__);
                TestCase::assertSame($this->expectedRepositoryDir, $dir);

                return $this->mockPatchVersions;
            }

        };
    }

    /**
     * @test
     */
    public function I_can_ask_it_if_code_has_specific_version(): void
    {
        $webVersions = new WebVersions(new Git(), __DIR__);
        self::assertTrue($webVersions->hasMinorVersion($webVersions->getLastUnstableVersion()));
        self::assertTrue($webVersions->hasMinorVersion($webVersions->getLastStableMinorVersion()));
        self::assertFalse($webVersions->hasMinorVersion('-1'));
    }
}