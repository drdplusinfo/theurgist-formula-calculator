<?php declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;

class GoogleTest extends AbstractContentTest
{
    /**
     * @test
     */
    public function Site_can_be_verified_for_google_search_console(): void
    {
        $googleSearchConsoleVerificationFile = $this->getProjectRoot() . '/google8d8724e0c2818dfc.html';
        self::assertFileExists($googleSearchConsoleVerificationFile);
        $verificationContent = \file_get_contents($googleSearchConsoleVerificationFile);
        self::assertSame(
            'google-site-verification: google8d8724e0c2818dfc.html',
            $verificationContent,
            'Expected different content in Google Search console verification file '
            . \basename($this->getProjectRoot()) . '/google8d8724e0c2818dfc.html'
        );
    }

    /**
     * @test
     */
    public function Google_analytics_are_active(): void
    {
        $htmlDocument = $this->getHtmlDocument(['mode' => 'prod']);
        $scripts = $htmlDocument->head->getElementsByTagName('script');
        self::assertNotEmpty($scripts, 'No scripts found in head ' . $htmlDocument->head->outerHTML);
        $sourcesToScripts = [];
        $googleAnalyticsScript = null;
        foreach ($scripts as $script) {
            $source = $script->getAttribute('src');
            $sourcesToScripts[$source] = $script;
            if (\strpos($source, 'google-analytics.js') !== false) {
                $googleAnalyticsScript = $script;
            }
        }
        if (!$googleAnalyticsScript) {
            self::fail('Google analytics script is missing, available are only ' . \print_r(\array_keys($sourcesToScripts), true));
        }
        $expectedGoogleAnalyticsId = $this->getTestsConfiguration()->getExpectedGoogleAnalyticsId();
        $expectedGoogleTagManagerScriptLink = 'https://www.googletagmanager.com/gtag/js?id=' . $expectedGoogleAnalyticsId;
        $googleTagManagerScript = $sourcesToScripts[$expectedGoogleTagManagerScriptLink] ?? null;
        if (!$googleTagManagerScript) {
            self::fail("Google tag manager script is missing, was looking for '$expectedGoogleTagManagerScriptLink', available are only "
                . \print_r(\array_keys($sourcesToScripts), true)
            );
        }
        $googleAnalyticsScriptRelativeFile = \parse_url($googleAnalyticsScript->getAttribute('src'), \PHP_URL_PATH);
        $googleAnalyticsScriptFile = $this->getProjectRoot() . '/' . \ltrim($googleAnalyticsScriptRelativeFile, '/');
        self::assertFileExists($googleAnalyticsScriptFile, 'Can not find Google analytics script on expected path');
        $googleAnalyticsScriptContent = \file_get_contents($googleAnalyticsScriptFile);
        self::assertNotEmpty($googleAnalyticsScriptContent, "Google analytics script file is empty: $googleAnalyticsScriptFile");
        $expectedGoogleAnalyticsScriptContent = \file_get_contents(__DIR__ . '/../../../js/generic/skeleton/vendor/rules/google-analytics.js');
        self::assertSame(
            $expectedGoogleAnalyticsScriptContent,
            $googleAnalyticsScriptContent,
            'Google analytics script has unexpected content'
        );
    }
}