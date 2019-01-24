<?php
declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\RulesSkeleton\Web\DebugContactsBody;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;
use Gt\Dom\Element;

class ContactsContentTest extends AbstractContentTest
{
    /**
     * @test
     */
    public function Proper_facebook_link_is_used_in_debug_contacts(): void
    {
        self::assertContains(
            '"https://www.facebook.com/drdplus.info"',
            $this->getDebugContactsContent(),
            'Link to facebook.com/drdplus.info has not been found in debug contacts template'
        );
    }

    private function getDebugContactsContent(): string
    {
        static $debugContactsContent;
        if ($debugContactsContent === null) {
            $debugContactsBodyClass = $this->getDebugContactsBodyClass();
            /** @var DebugContactsBody $debugContactsBody */
            $debugContactsBody = new $debugContactsBodyClass();
            $debugContactsContent = $debugContactsBody->getValue();
        }

        return $debugContactsContent;
    }

    /**
     * @return string|DebugContactsBody
     */
    protected function getDebugContactsBodyClass(): string
    {
        return DebugContactsBody::class;
    }

    /**
     * @test
     */
    public function Proper_rpg_forum_link_is_used_in_debug_contacts(): void
    {
        self::assertContains(
            '"https://rpgforum.cz/forum/viewtopic.php?f=238&t=14870"',
            $this->getDebugContactsContent(),
            'Link to RPG forum has not been found in debug contacts template'
        );
    }

    /**
     * @test
     */
    public function I_can_use_mail_to_link_to_drdplus_info_email(): void
    {
        $debugContactsElement = $this->getDebugContactsElement();
        if (!$this->getTestsConfiguration()->hasDebugContacts()) {
            self::assertNull($debugContactsElement, 'Debug contacts have not been expected');

            return;
        }
        $this->guardDebugContactsAreNotEmpty($debugContactsElement);
        $anchors = $debugContactsElement->getElementsByTagName('a');
        self::assertNotEmpty($anchors, 'No anchors found in debug contacts');
        $mailTo = null;
        foreach ($anchors as $anchor) {
            $href = (string)$anchor->getAttribute('href');
            if (!$href || \strpos($href, 'mailto:') !== 0) {
                continue;
            }
            $mailTo = $href;
        }
        self::assertNotEmpty($mailTo, 'Missing mailto: in debug contacts ' . $debugContactsElement->innerHTML);
        self::assertSame('mailto:info@drdplus.info', $mailTo);
    }

    private function getDebugContactsElement(): ?Element
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->getHtmlDocument()->getElementById(HtmlHelper::ID_DEBUG_CONTACTS);
    }

    private function guardDebugContactsAreNotEmpty(?Element $debugContactsElement): void
    {
        self::assertNotEmpty($debugContactsElement, 'Debug contacts has not been found by ID debug_contacts (debugContacts)');
        self::assertNotEmpty($debugContactsElement->textContent, 'Debug contacts are empty');
    }

}