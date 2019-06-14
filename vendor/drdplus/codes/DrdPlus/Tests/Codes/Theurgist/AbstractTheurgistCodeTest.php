<?php declare(strict_types=1);

namespace DrdPlus\Tests\Codes\Theurgist;

use DrdPlus\Codes\Theurgist\AbstractTheurgistCode;
use DrdPlus\Tests\Codes\Partials\TranslatableCodeTest;

abstract class AbstractTheurgistCodeTest extends TranslatableCodeTest
{
    protected function setUp()
    {
        self::assertContains(__NAMESPACE__, static::class, 'Code test has to be in "Tests" namespace');
    }

    /**
     * @test
     */
    public function I_can_get_its_english_translation()
    {
        /** @var AbstractTheurgistCode $sutClass */
        $sutClass = self::getSutClass();
        foreach ($sutClass::getPossibleValues() as $value) {
            /** @var AbstractTheurgistCode $sut */
            $sut = $sutClass::getIt($value);
            self::assertSame($this->codeToEnglish($value), $sut->translateTo('en'));
        }
    }

    /**
     * @param string $code
     * @return string
     */
    private function codeToEnglish(string $code): string
    {
        return str_replace(['venus', 'mars', '_'], ['♀', '♂', ' '], $code);
    }

    /**
     * @test
     */
    public function I_can_get_its_czech_translation()
    {
        /** @var AbstractTheurgistCode $sutClass */
        $sutClass = self::getSutClass();
        foreach ($sutClass::getPossibleValues() as $value) {
            /** @var AbstractTheurgistCode $sut */
            $sut = $sutClass::getIt($value);
            $inEnglish = $this->codeToEnglish($value);
            self::assertSame($inEnglish, $sut->translateTo('en'));
            if (in_array($value, $this->getValuesSameInCzechAndEnglish(), true)) {
                self::assertSame($inEnglish, $sut->translateTo('cs'));
            } else {
                self::assertNotSame(
                    $inEnglish,
                    $sut->translateTo('cs'),
                    "Expected '{$value}' to be different in czech than in english"
                );
            }
        }
    }

    /**
     * @return array|string[]
     */
    abstract protected function getValuesSameInCzechAndEnglish(): array;

    /**
     * @test
     */
    public function I_can_get_original_value()
    {
        /** @var AbstractTheurgistCode $sutClass */
        $sutClass = self::getSutClass();
        foreach ($sutClass::getPossibleValues() as $value) {
            /** @var AbstractTheurgistCode $sut */
            $sut = $sutClass::getIt($value);
            self::assertSame($this->codeToEnglish($value), $sut->translateTo('en'));
        }
    }

    /**
     * @test
     */
    public function I_get_warning_for_unknown_locale()
    {
        /** @var AbstractTheurgistCode $sutClass */
        $sutClass = self::getSutClass();
        foreach ($sutClass::getPossibleValues() as $value) {
            /** @var AbstractTheurgistCode $sut */
            $sut = $sutClass::getIt($value);
            $inEnglish = $this->codeToEnglish($value);
            $previousErrorReporting = error_reporting(-1 ^ E_USER_WARNING);
            error_clear_last();
            self::assertSame($inEnglish, @$sut->translateTo('demonic'));
            $lastError = error_get_last();
            error_reporting($previousErrorReporting);
            error_clear_last();
            self::assertNotEmpty($lastError);
            self::assertSame(E_USER_WARNING, $lastError['type']);
            self::assertContains($value, $lastError['message']);
            self::assertContains('demonic', $lastError['message']);
        }
    }
}