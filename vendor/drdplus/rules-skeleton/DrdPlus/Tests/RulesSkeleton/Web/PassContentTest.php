<?php
declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton\Web;

use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;

class PassContentTest extends AbstractContentTest
{
    /**
     * @test
     */
    public function Headings_do_not_have_anchors_to_self(): void
    {
        if (!$this->isSkeletonChecked() && !$this->getTestsConfiguration()->hasProtectedAccess()) {
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
}
