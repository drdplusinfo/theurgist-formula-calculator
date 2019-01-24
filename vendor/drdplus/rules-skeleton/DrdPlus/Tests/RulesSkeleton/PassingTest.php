<?php
namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\RulesSkeleton\UsagePolicy;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;
use Gt\Dom\Element;
use Gt\Dom\HTMLDocument;

class PassingTest extends AbstractContentTest
{

    /**
     * @test
     */
    public function I_have_to_confirm_owning_of_a_licence_first(): void
    {
        if (!$this->getTestsConfiguration()->hasProtectedAccess()) {
            self::assertFalse(
                false,
                'Text-only and free content is accessible for anyone and licence need not to be confirmed'
            );

            return;
        }
        $html = new HTMLDocument($this->getPassContent());
        $forms = $html->getElementsByTagName('form');
        self::assertCount(3, $forms);
        foreach ($forms as $index => $form) {
            switch ($index) {
                case 0:
                    $this->I_can_continue_with_trial($form);
                    break;
                case 1:
                    $this->I_can_buy_licence($form);
                    break;
                case 2:
                    $this->I_can_continue_after_confirmation_of_owning($form);
            }
        }
    }

    private function I_can_buy_licence(Element $buyForm): void
    {
        self::assertStringStartsWith('https://obchod.altar.cz', $buyForm->getAttribute('action'));
        self::assertRegExp(
            '~^' . preg_quote('https://obchod.altar.cz/', '~') . '\w+~',
            $buyForm->getAttribute('action'),
            'Missing direct link to current article in e-shop, (put it into eshop_url.txt file)'
        );
        self::assertContains((string)$buyForm->getAttribute('method'), ['' /* get as default */, 'get']);
        self::assertSame('buy', $buyForm->getElementsByTagName('button')->current()->getAttribute('name'));
        self::assertEmpty($buyForm->getAttribute('onsubmit'), 'No confirmation should be required to access e-shop');
    }

    private function I_can_continue_after_confirmation_of_owning(Element $confirmForm): void
    {
        self::assertSame('post', $confirmForm->getAttribute('method'));
        $buttons = $confirmForm->getElementsByTagName('button');
        self::assertNotEmpty($buttons);
        $confirmButton = null;
        foreach ($buttons as $button) {
            if ($button->getAttribute('name') === 'confirm') {
                $confirmButton = $button;
            }
        }
        self::assertNotNull($confirmButton, "Missing button[name=confirm] in \n" . $confirmForm->outerHTML);
    }

    private function I_can_continue_with_trial(Element $trialForm): void
    {
        self::assertSame('post', $trialForm->getAttribute('method'));
        self::assertSame('trial', $trialForm->getElementsByTagName('button')->current()->getAttribute('name'));
        self::assertEmpty($trialForm->getAttribute('onsubmit'), 'No confirmation should be required for trial access');
    }

    /**
     * @test
     */
    public function I_can_confirm_ownership(): void
    {
        $html = new HTMLDocument($this->getContent()); // includes confirmation via cookie
        $forms = $html->getElementsByTagName('form');
        self::assertCount(0, $forms, 'No forms expected in confirmed content');
    }

    /**
     * @test
     * @backupGlobals enabled
     */
    public function Crawlers_can_pass_without_licence_owning_confirmation(): void
    {
        if (!$this->isSkeletonChecked() && !$this->getTestsConfiguration()->hasProtectedAccess()) {
            self::assertTrue(true, 'Crawlers can access content as anyone else');

            return;
        }
        $passContent = $this->getPassContent(true /* not cached */);
        $this->passIn();
        $rulesContent = $this->fetchNonCachedContent();
        $this->passOut();
        $passContentDocument = new HTMLDocument($passContent);
        $rulesContentDocument = new HTMLDocument($rulesContent);
        self::assertNotSame(
            $passContentDocument->body->innerHTML,
            $rulesContentDocument->body->innerHTML,
            'Expected pass for a non-bot visitor'
        );
        foreach (RequestTest::getCrawlerUserAgents() as $crawlerUserAgent) {
            $_SERVER['HTTP_USER_AGENT'] = $crawlerUserAgent;
            $passContent = $this->getPassContent(true /* not cached */);
            $rulesContent = $this->fetchNonCachedContent();
            $passContentDocument = new HTMLDocument($passContent);
            $rulesContentDocument = new HTMLDocument($rulesContent);
            self::assertSame(
                \preg_replace('~' . HtmlHelper::DATA_CACHED_AT . '="[^"]+"~', '', $passContentDocument->body->innerHTML),
                \preg_replace('~' . HtmlHelper::DATA_CACHED_AT . '="[^"]+"~', '', $rulesContentDocument->body->innerHTML),
                'Expected rules content for a crawler, skipping ownership confirmation page'
            );
        }
    }

    /**
     * @test
     * @backupGlobals enabled
     */
    public function I_see_message_about_trial_expiration_if_happens(): void
    {
        if (!$this->getTestsConfiguration()->hasProtectedAccess()) {
            self::assertFalse(false, 'Free content does not have trial');

            return;
        }
        $this->passOut();
        $warningsOnFirstVisit = $this->getHtmlDocument()->getElementsByClassName('warning');
        self::assertCount(0, $warningsOnFirstVisit, 'No warnings expected so far');
        $warningsOnTrialExpiration = $this->getHtmlDocument([UsagePolicy::TRIAL_EXPIRED_AT => time() - 1])
            ->getElementsByClassName('warning');
        self::assertCount(1, $warningsOnTrialExpiration, 'Expected single warning about trial expiration');
        /** @var Element $warningAboutTrialExpiration */
        $warningAboutTrialExpiration = $warningsOnTrialExpiration->current();
        self::assertSame('⌛ Čas tvého testování se naplnil ⌛', $warningAboutTrialExpiration->textContent);
    }
}