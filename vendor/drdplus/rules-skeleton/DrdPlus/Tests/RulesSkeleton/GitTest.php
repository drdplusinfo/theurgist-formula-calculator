<?php
declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;

class GitTest extends AbstractContentTest
{
    /**
     * @test
     */
    public function Generic_assets_are_versioned(): void
    {
        foreach (['css/generic', 'images/generic', 'js/generic'] as $assetsDir) {
            ['output' => $output, 'result' => $result] = $this->getGitFolderIgnoring($assetsDir);
            /** @noinspection DisconnectedForeachInstructionInspection */
            self::assertLessThanOrEqual(1, $result); // GIT check-ignore results into 1 if dir is not ignored
            self::assertSame([], $output, "The $assetsDir dir should be versioned, but is ignored");
        }
    }

    /**
     * @test
     */
    public function Vendor_dir_is_versioned_as_well(): void
    {
        ['output' => $output, 'result' => $result] = $this->getGitFolderIgnoring($this->getVendorRoot());
        if ($this->isSkeletonChecked()) {
            self::assertSame(0, $result);
            self::assertSame([$this->getVendorRoot()], $output, 'The vendor dir should be ignored for skeleton');
        } else {
            self::assertLessThanOrEqual(
                1, // GIT check-ignore results into 1 if dir is not ignored
                $result,
                "Vendor dir should not be ignored for final project ({$this->getVendorRoot()})"
            );
            self::assertSame([], $output, "The vendor dir should be versioned, but is ignored ({$this->getVendorRoot()})");
        }
    }

    /**
     * @test
     */
    public function Local_project_config_is_ignored(): void
    {
        ['output' => $output, 'result' => $result] = $this->getGitFolderIgnoring('config.local.yml');
        self::assertSame(0, $result, 'config.local.yml should be ignored'); // GIT check-ignore results into 0 if dir is ignored
        self::assertSame(['config.local.yml'], $output, 'config.local.yml should be ignored');
    }

    /**
     * @test
     */
    public function Cache_dir_is_ignored(): void
    {
        $cacheBaseRoot = \dirname($this->getDirs()->getCacheRoot()); // cache/cli => cache
        $cacheGitIgnore = $cacheBaseRoot . '/.gitignore';
        self::assertFileExists($cacheGitIgnore, 'Expected .gitignore in cache dir');
        self::assertSame(<<<TEXT
*
!/.gitignore
TEXT
            , \file_get_contents($cacheGitIgnore),
            'Expected different content in ' . $cacheGitIgnore
        );
    }
}