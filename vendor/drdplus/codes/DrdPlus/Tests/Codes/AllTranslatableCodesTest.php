<?php
namespace DrdPlus\Tests\Codes;

use DrdPlus\Codes\Armaments\ShieldCode;
use DrdPlus\Codes\Armaments\WeaponCategoryCode;
use DrdPlus\Codes\Body\OrdinaryWoundOriginCode;
use DrdPlus\Codes\Body\SeriousWoundOriginCode;
use DrdPlus\Codes\Environment\LandingSurfaceCode;
use DrdPlus\Codes\Environment\MaterialCode;
use DrdPlus\Codes\GenderCode;
use DrdPlus\Codes\ItemHoldingCode;
use DrdPlus\Codes\Partials\TranslatableCode;
use DrdPlus\Codes\RaceCode;
use DrdPlus\Codes\Skills\SkillCode;
use DrdPlus\Codes\Skills\SkillTypeCode;
use DrdPlus\Codes\SubRaceCode;
use DrdPlus\Codes\Theurgist\AffectionPeriodCode;
use DrdPlus\Codes\Theurgist\DemonBodyCode;
use DrdPlus\Codes\Theurgist\DemonCode;
use DrdPlus\Codes\Theurgist\DemonKindCode;
use DrdPlus\Codes\Theurgist\DemonMutableParameterCode;
use DrdPlus\Codes\Theurgist\DemonTraitCode;
use DrdPlus\Codes\Theurgist\FormCode;
use DrdPlus\Codes\Theurgist\FormulaCode;
use DrdPlus\Codes\Theurgist\FormulaMutableParameterCode;
use DrdPlus\Codes\Theurgist\ModifierCode;
use DrdPlus\Codes\Theurgist\ModifierMutableParameterCode;
use DrdPlus\Codes\Theurgist\ProfileCode;
use DrdPlus\Codes\Theurgist\SpellTraitCode;
use DrdPlus\Codes\Wizard\SpellCode;
use DrdPlus\Tests\Codes\Partials\TranslatableCodeTest;
use Granam\Tests\Tools\TestWithMockery;

class AllTranslatableCodesTest extends TestWithMockery
{

    use GetCodeClassesTrait;

    /**
     * @test
     * @throws \ReflectionException
     */
    public function I_can_get_its_english_translation()
    {
        foreach ($this->getTranslatableCodeClasses() as $codeClass) {
            $testClass = $this->getTestClass($codeClass);
            self::assertTrue(
                is_a($testClass, TranslatableCodeTest::class, true),
                "Test class $testClass should be a descendant of " . TranslatableCodeTest::class
            );
            $hasSinglesOnly = $this->hasSinglesOnly($codeClass);
            $hasMultiplesOnly = $this->hasMultiplesOnly($codeClass);
            foreach ($codeClass::getPossibleValues() as $value) {
                /** @var TranslatableCode $sut */
                $sut = $codeClass::getIt($value);
                self::assertSame($this->codeToEnglish($value), $sut->translateTo('en'));
                self::assertSame($sut->translateTo('en'), $sut->translateTo('en', 1));
                if ($hasSinglesOnly || $hasMultiplesOnly || in_array($value, $this->getValuesSameInEnglishForAnyNumbers(), true)) {
                    self::assertSame($sut->translateTo('en', 1), $sut->translateTo('en', 2));
                } else {
                    self::assertNotSame(
                        $one = $sut->translateTo('en', 1),
                        $two = $sut->translateTo('en', 2),
                        "Expected different translation in english from $codeClass for numbers 1 and 2: $one, $two"
                    );
                }
                self::assertSame($sut->translateTo('en', 2), $sut->translateTo('en', 3));
                self::assertSame($sut->translateTo('en', 2), $sut->translateTo('en', 999));
            }
        }
    }

    protected function getValuesSameInEnglishForAnyNumbers(): array
    {
        return [
            'senses',
            'unarmed',
        ];
    }

    protected function hasSinglesOnly(string $codeClass): bool
    {
        foreach (
            [
                SkillTypeCode::class, SkillCode::class, ShieldCode::class, ItemHoldingCode::class,
                LandingSurfaceCode::class, MaterialCode::class, SeriousWoundOriginCode::class,
                OrdinaryWoundOriginCode::class, RaceCode::class, SubRaceCode::class, GenderCode::class,
                SpellCode::class, FormulaMutableParameterCode::class, FormCode::class, DemonCode::class,
                DemonMutableParameterCode::class, AffectionPeriodCode::class, DemonTraitCode::class,
                DemonBodyCode::class, FormulaCode::class, ModifierMutableParameterCode::class, ProfileCode::class,
                ModifierCode::class, SpellTraitCode::class, DemonKindCode::class,
            ] as $singleOnlyClass
        ) {
            if (is_a($codeClass, $singleOnlyClass, true)) {
                return true;
            }
        }

        return false;
    }

    protected function hasMultiplesOnly(string $codeClass): bool
    {
        foreach ([WeaponCategoryCode::class] as $multipleOnlyClass) {
            if (is_a($codeClass, $multipleOnlyClass, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array|TranslatableCode[]
     * @throws \ReflectionException
     */
    private function getTranslatableCodeClasses(): array
    {
        $translatableCodeClasses = [];
        foreach ($this->getCodeClasses() as $codeClass) {
            if (!is_a($codeClass, TranslatableCode::class, true)) {
                continue;
            }
            $translatableCodeClasses[] = $codeClass;
        }

        return $translatableCodeClasses;
    }

    private function getTestClass(string $codeClass): string
    {
        $testClass = str_replace('DrdPlus\\', 'DrdPlus\Tests\\', $codeClass) . 'Test';
        self::assertTrue(class_exists($testClass), "Estimated test class $testClass does not exists");

        return $testClass;
    }

    /**
     * @param string $code
     * @return string
     */
    protected function codeToEnglish(string $code): string
    {
        return preg_replace(
            ['~_dm_~', '~_~', '~run silently~', '~venus$~', '~mars$~'],
            ['_DM_', ' ', 'run silently!', '♀', '♂'],
            $code
        );
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function I_can_get_its_czech_translation()
    {
        foreach ($this->getTranslatableCodeClasses() as $codeClass) {
            $hasSinglesOnly = $this->hasSinglesOnly($codeClass);
            $hasMultiplesOnly = $this->hasMultiplesOnly($codeClass);
            foreach ($codeClass::getPossibleValues() as $value) {
                /** @var TranslatableCode $sut */
                $sut = $codeClass::getIt($value);
                $oneInEnglish = $this->codeToEnglish($value);
                $oneInCzech = $sut->translateTo('cs');
                self::assertNotContains('_', $oneInCzech, 'Underscore is really weird in a translation');
                self::assertSame($oneInCzech, $sut->translateTo('cs', 1));
                if (in_array($oneInEnglish, $this->getValuesSameInCzechAndEnglish(), true)) {
                    self::assertSame($oneInEnglish, $oneInCzech);
                } else {
                    self::assertNotSame(
                        $oneInEnglish,
                        $oneInCzech,
                        "Expected translation of '{$value}' to be different in czech than in english in code {$codeClass}, got '{$oneInCzech}'"
                    );
                }
                $twoInCzech = $sut->translateTo('cs', 2);
                if ($hasSinglesOnly || $hasMultiplesOnly || in_array($oneInCzech, $this->getValuesSameInCzechForOneAndFew(), true)) {
                    self::assertSame(
                        $oneInCzech,
                        $twoInCzech,
                        "Expected same translation in czech from $codeClass for numbers 1 and 2: $oneInCzech, $twoInCzech"
                    );
                } else {
                    self::assertNotSame(
                        $oneInCzech,
                        $twoInCzech,
                        "Expected different translation in czech from $codeClass for numbers 1 and 2: $oneInCzech, $twoInCzech"
                    );
                }
                self::assertSame($twoInCzech, $threeInCzech = $sut->translateTo('cs', 3));
                self::assertSame($threeInCzech, $fourInCzech = $sut->translateTo('cs', 4));
                $fiveInCzech = $sut->translateTo('cs', 5);
                if ($hasSinglesOnly || $hasMultiplesOnly || in_array($fourInCzech, $this->getValuesSameInCzechForFewAndMany(), true)) {
                    self::assertSame($fourInCzech, $fiveInCzech);
                } else {
                    self::assertNotSame(
                        $fourInCzech,
                        $fiveInCzech,
                        "Expected different translation in czech from $codeClass for numbers 4 and 5: $fourInCzech, $fiveInCzech"
                    );
                }
                self::assertSame($fiveInCzech, $sut->translateTo('cs', 6));
                self::assertSame($fiveInCzech, $sut->translateTo('cs', 999));
            }
        }
    }

    /**
     * @return array|string[]
     */
    protected function getValuesSameInCzechAndEnglish(): array
    {
        return [
            'charisma', 'pony', 'elf', 'kroll', 'skurut', 'goblin',
            'nekrakosa', 'genius loci', 'berserk', 'delirium', // wizard
            'golem', 'receptor ♀', 'receptor ♂', 'receptor', 'amulet' // theurgist
        ];
    }

    /**
     * @return array|string[]
     */
    protected function getValuesSameInCzechForOneAndFew(): array
    {
        return [
            'vůle',
            'inteligence',
            'smysly',
            'maximální naložení',
            'infravize',
            'šavle',
            'kopí',
            'vidle',
            'minikuše',
            'hvězdice',
            'beze zbraně',
            'kuše',
            'pony',
            'stání',
        ];
    }

    /**
     * @return array|string[]
     */
    protected function getValuesSameInCzechForFewAndMany(): array
    {
        return [
            'smysly',
            'kopí',
            'beze zbraně',
            'kněží',
            'pony',
            'stání',
            'beze zbrojí',
            'bez helem',
        ];
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function I_get_warning_for_unknown_locale()
    {
        foreach ($this->getTranslatableCodeClasses() as $codeClass) {
            foreach ($codeClass::getPossibleValues() as $value) {
                /** @var TranslatableCode $sut */
                $sut = $codeClass::getIt($value);
                $inEnglish = $this->codeToEnglish($value);
                $previousErrorReporting = error_reporting(-1 ^ E_USER_WARNING);
                error_clear_last();
                self::assertSame($inEnglish, $sut->translateTo('demonic'));
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
}