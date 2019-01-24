<?php
declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton\Web;

use DrdPlus\RulesSkeleton\Configuration;
use DrdPlus\RulesSkeleton\Web\Menu;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;
use Granam\WebContentBuilder\HtmlDocument;
use Gt\Dom\Element;

class MenuTest extends AbstractContentTest
{
    /**
     * @test
     */
    public function I_can_hide_home_button(): void
    {
        $configurationWithShownHomeButton = $this->createCustomConfiguration(
            [Configuration::WEB => [Configuration::SHOW_HOME_BUTTON => true]]
        );
        self::assertTrue($configurationWithShownHomeButton->isShowHomeButton(), 'Expected configuration with shown home button');
        $menuWithHomeButton = $this->createMenu($configurationWithShownHomeButton);
        if ($this->isSkeletonChecked()) {
            $htmlDocument = new HtmlDocument(<<<HTML
<html lang="cs">
<body>
{$menuWithHomeButton->getValue()}
</body>
</html>
HTML
            );
            /** @var Element $homeButton */
            $homeButton = $htmlDocument->getElementById('homeButton');
            self::assertNotEmpty($homeButton, 'Home button is missing');
            self::assertSame(
                'https://www.drdplus.info',
                $homeButton->getAttribute('href'), 'Link of home button should lead to home'
            );
        }
        $configurationWithHiddenHomeButton = $this->createCustomConfiguration(
            [Configuration::WEB => [Configuration::SHOW_HOME_BUTTON => false]]
        );
        self::assertFalse($configurationWithHiddenHomeButton->isShowHomeButton(), 'Expected configuration with hidden home button');
        $menuWithoutHomeButton = $this->createMenu($configurationWithHiddenHomeButton);
        if ($this->isSkeletonChecked()) {
            $htmlDocument = new HtmlDocument(<<<HTML
<html lang="cs">
<body>
{$menuWithoutHomeButton->getValue()}
</body>
</html>
HTML
            );
            $homeButton = $htmlDocument->getElementById('homeButton');
            self::assertEmpty($homeButton, 'Home button should not be used at all');
        }
    }

    private function createMenu(Configuration $configuration): Menu
    {
        return new Menu(
            $configuration,
            $this->createWebVersions(),
            $this->createCurrentWebVersion(),
            $this->createRequest()
        );
    }

}