<?php
declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;

class PageTitleTest extends AbstractContentTest
{

    /**
     * @test
     */
    public function Page_has_title(): void
    {
        $expectedPageTitle = $this->getTestsConfiguration()->getExpectedPageTitle();
        $currentPageTitle = $this->getCurrentPageTitle();
        self::assertNotEmpty($currentPageTitle, 'Page title is missing on page');
        self::assertSame($expectedPageTitle, $currentPageTitle, 'Current page title differs from expected one');
        $rulesTitle = $this->getCurrentPageTitle($this->getHtmlDocument());
        self::assertNotEmpty($rulesTitle, 'Rules title is missing');
        $passTitle = $this->getCurrentPageTitle($this->getPassDocument());
        self::assertNotEmpty($passTitle, 'Pass title is missing');
        self::assertSame($rulesTitle, $passTitle, 'Rules and pass titles should be the same');
    }
}