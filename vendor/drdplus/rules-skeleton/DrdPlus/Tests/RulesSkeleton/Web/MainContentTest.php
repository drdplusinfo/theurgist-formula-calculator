<?php
declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton\Web;

use DrdPlus\RulesSkeleton\Web\RulesMainContent;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;

class MainContentTest extends AbstractContentTest
{
    /**
     * @test
     */
    public function I_can_get_content(): void
    {
        self::assertSame($this->getHtmlDocument()->saveHTML(), $this->getContent());
    }

    /**
     * @test
     */
    public function I_can_get_body(): void
    {
        self::assertNotEmpty($this->getHtmlDocument()->body->innerHTML);
    }

    /**
     * @test
     */
    public function Body_has_container_bootstrap_class(): void
    {
        self::assertTrue(
            $this->getHtmlDocument()->body->classList->contains('container'),
            'Body should has "container" class to be usable for Bootstrap'
        );
    }

    /**
     * @test
     * @dataProvider provideLinkToDrdPlus
     * @param string $linkToDrdPlus
     * @param string $expectedLinkWithoutDiacritics
     */
    public function Link_to_drdplus_is_has_removed_diacritics_from_hash(
        string $linkToDrdPlus,
        string $expectedLinkWithoutDiacritics
    ): void
    {
        $rulesMainContent = new RulesMainContent(
            $this->createHtmlHelper(),
            $this->createEmptyHead(),
            $this->createMainBody(sprintf('<a href="%s">Some link</a>', $linkToDrdPlus))
        );
        self::assertContains($expectedLinkWithoutDiacritics, $rulesMainContent->getValue());
    }

    public function provideLinkToDrdPlus(): array
    {
        return [
            ['https://pph.drdplus.info/foo#Příprava postavy', 'http://pph.drdplus.loc/foo#priprava_postavy'],
            ['https://bojovnik.drdplus.info/#Nekonečné kopí', 'http://bojovnik.drdplus.loc/#nekonecne_kopi'],
        ];
    }

    /**
     * @test
     * @dataProvider provideLinkOutOfDrdPlus
     * @param string $linkOutOfDrdPlus
     */
    public function Link_outside_of_drdplus_has_untouched_diacritics_in_hash(string $linkOutOfDrdPlus): void
    {
        self::assertNotRegExp('~drdplus[.](loc|info)~', $linkOutOfDrdPlus);
        $rulesMainContent = new RulesMainContent(
            $this->createHtmlHelper(),
            $this->createEmptyHead(),
            $this->createMainBody(\sprintf('<a href="%s">Some link</a>', $linkOutOfDrdPlus))
        );
        $hash = \substr($linkOutOfDrdPlus, \strpos($linkOutOfDrdPlus, '#') + 1);
        $expectedLinkOutOfDrdPlus = \str_replace('#' . $hash, '#' . \rawurlencode($hash), $linkOutOfDrdPlus);
        self::assertContains($expectedLinkOutOfDrdPlus, $rulesMainContent->getValue());
    }

    public function provideLinkOutOfDrdPlus(): array
    {
        return [
            ['https://obchod.altar.cz/drd-sada-zakladnich-prirucek-p-295.html#Copak to tu máme'],
        ];
    }

    /**
     * @test
     */
    public function Link_to_blog_is_not_broken(): void
    {
        $rulesMainContent = new RulesMainContent(
            $this->createHtmlHelper(),
            $this->createEmptyHead(),
            $this->createMainBody('<a href="https://blog.drdplus.info/#!index.md">To blog</a>')
        );
        self::assertContains('http://blog.drdplus.loc/#!index.md', $rulesMainContent->getValue());
    }
}