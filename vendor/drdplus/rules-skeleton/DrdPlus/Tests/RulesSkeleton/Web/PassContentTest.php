<?php declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton\Web;

use DrdPlus\RulesSkeleton\Request;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;
use Granam\WebContentBuilder\HtmlDocument;

class PassContentTest extends AbstractContentTest
{
    /**
     * @test
     */
    public function Headings_do_not_have_anchors_to_self(): void
    {
        if (!$this->getTestsConfiguration()->hasProtectedAccess()) {
            self::assertFalse(false, 'Nothing to test here');

            return;
        }
        $someHeadingsChecked = false;
        foreach (['h1', 'h2', 'h3', 'h4', 'h5', 'h6'] as $headingTag) {
            foreach ($this->getPassDocument()->getElementsByTagName($headingTag) as $heading) {
                self::assertCount(
                    0,
                    $heading->getElementsByTagName('a'),
                    'No anchors expected in pass heading ' . $heading->outerHTML
                );
                $someHeadingsChecked = true;
            }
        }
        self::assertTrue($someHeadingsChecked, 'Some headings expected on pass');
    }

    /**
     * @test
     */
    public function Pass_does_not_reset_requested_path_and_query(): void
    {
        if (!$this->getTestsConfiguration()->hasProtectedAccess()) {
            self::assertFalse(false, 'Nothing to test here');

            return;
        }
        $someActionsChecked = false;
        $expectedLocalUrl = '/foo?qux=baz';
        $localUrl = $expectedLocalUrl . '&' . Request::TRIAL_EXPIRED_AT . '=123456';
        $url = $this->getTestsConfiguration()->getLocalUrl() . $localUrl;
        $content = $this->fetchContentFromUrl($url, true)['content'];
        self::assertNotEmpty($content, 'No content fetched from ' . $url);

        $pass = new HtmlDocument($content);
        foreach ($pass->getElementsByTagName('form') as $form) {
            $action = $form->getAttribute('action');
            if (!preg_match('~^/[^/]*$~', $action)) {
                continue; // not a local link
            }
            self::assertSame($expectedLocalUrl, $action, 'Expected local URL without trial expiration parameter');
            $someActionsChecked = true;
        }
        self::assertTrue($someActionsChecked, 'No forms with a local link found on URL ' . $url);
    }
}
