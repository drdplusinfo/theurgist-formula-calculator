<?php declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\CookiesService;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;

class WebContentVersionTest extends AbstractContentTest
{

    /**
     * @test
     */
    public function Every_version_like_branch_has_detailed_version_tags(): void
    {
        $webVersions = $this->createWebVersions();
        $patchTags = [];
        $allTags = $this->runCommand('git tag');
        foreach ($allTags as $tag) {
            if (preg_match('~^v?(\d+[.]){2}\d+$~', $tag)) {
                $patchTags[] = $tag;
            }
        }
        $allStableMinorVersions = $webVersions->getAllStableMinorVersions();
        if (!$allStableMinorVersions) {
            self::assertEmpty($patchTags, 'No patch tags expected as there are no version-like branches');

            return;
        }
        self::assertNotEmpty(
            $patchTags,
            'Some patch-version tags expected for versions: '
            . \implode(',', $allStableMinorVersions)
        );
        foreach ($allStableMinorVersions as $stableMinorVersion) {
            $stableVersionTags = [];
            foreach ($allTags as $tag) {
                if (\strpos($tag, $stableMinorVersion) === 0) {
                    $stableVersionTags[] = $tag;
                }
            }
            self::assertNotEmpty($stableVersionTags, "No tags found for $stableMinorVersion, got only " . \print_r($allTags, true));
        }
    }

    /**
     * @test
     * @backupGlobals enabled
     */
    public function Current_version_is_written_into_cookie(): void
    {
        $application = $this->createRulesApplication($servicesContainer = $this->getServicesContainer());
        $this->fetchNonCachedContent($application, false /* keep changed globals */);
        self::assertNotEmpty(
            $servicesContainer->getCookiesService()->getCookie(CookiesService::VERSION),
            sprintf('Missing %s in cookies', CookiesService::VERSION)
        );
    }
}