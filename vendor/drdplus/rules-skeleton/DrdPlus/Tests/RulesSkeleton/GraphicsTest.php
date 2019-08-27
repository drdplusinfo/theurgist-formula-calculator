<?php declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;

class GraphicsTest extends AbstractContentTest
{
    /**
     * @test
     */
    public function Main_page_has_monochrome_background_image(): void
    {
        self::assertFileExists($this->getProjectRoot() . '/images/main-background.png');
    }

    /**
     * @test
     */
    public function Main_page_uses_generic_image_for_background(): void
    {
        self::assertFileExists($this->getProjectRoot() . '/images/generic/skeleton/rules-background.png');
    }

    /**
     * @test
     */
    public function Licence_page_has_colored_background_image(): void
    {
        if (!$this->getTestsConfiguration()->hasProtectedAccess()) {
            self::assertFalse(false, 'Licence background image is not needed for free content');

            return;
        }
        self::assertFileExists($this->getProjectRoot() . '/images/licence-background.png');
    }
}