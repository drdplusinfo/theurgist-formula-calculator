<?php declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;
use Granam\String\StringTools;

class TestsConfigurationTest extends AbstractContentTest
{

    /**
     * @test
     * @throws \ReflectionException
     */
    public function I_can_use_it(): void
    {
        $testsConfiguration = $this->createTestsConfiguration();
        $booleanGettersWithDefaultFalse = ['hasShownHomeButton', 'hasLocalRepositories'];
        foreach ($this->getBooleanGetters() as $hasGetter) {
            if (in_array($hasGetter, $booleanGettersWithDefaultFalse, true)) {
                self::assertFalse(
                    $testsConfiguration->$hasGetter(),
                    "$hasGetter should return false as it is deprecated and replaced by another configurations"
                );
            } else {
                self::assertTrue(
                    $testsConfiguration->$hasGetter(),
                    "$hasGetter should return true by default to ensure strict mode"
                );
            }
        }
    }

    /**
     * @throws \ReflectionException
     */
    protected function getBooleanGetters(): array
    {
        $testsConfigurationReflection = new \ReflectionClass(static::getSutClass());
        $methods = $testsConfigurationReflection->getMethods(
            \ReflectionMethod::IS_PUBLIC ^ \ReflectionMethod::IS_STATIC ^ \ReflectionMethod::IS_ABSTRACT
        );
        $booleanGetters = [];
        foreach ($methods as $method) {
            $methodName = $method->getName();
            if (\strpos($methodName, 'has') === 0 || \strpos($methodName, 'can') === 0 || \strpos($methodName, 'is') === 0) {
                $booleanGetters[] = $methodName;
            }
        }
        return $booleanGetters;
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function I_can_disable_every_boolean_option()
    {
        $booleanOptionNames = $this->getBooleanOptionNames();
        foreach ($booleanOptionNames as $booleanOptionName) {
            $testsConfiguration = $this->createTestsConfiguration([$booleanOptionName => false]);
            $testedOptionGetter = StringTools::assembleGetterForName($booleanOptionName, '');
            self::assertFalse(
                $testsConfiguration->$testedOptionGetter(),
                "$testedOptionGetter() should return false in this test, is that value properly initialized in constructor?"
            );
        }
    }

    /**
     * @throws \ReflectionException
     */
    protected function getBooleanOptionNames(): array
    {
        $sutClasses = array_unique([TestsConfiguration::class, static::getSutClass()]);
        $booleanProperties = [];
        foreach ($sutClasses as $sutClass) {
            $testsConfigurationReflection = new \ReflectionClass($sutClass);
            $properties = $testsConfigurationReflection->getProperties(~\ReflectionProperty::IS_STATIC);
            $testsConfiguration = $this->getTestsConfiguration($sutClass);
            foreach ($properties as $property) {
                $property->setAccessible(true);
                if (is_bool($property->getValue($testsConfiguration))) {
                    $booleanProperties[] = StringTools::camelCaseToSnakeCase($property->getName());
                }
            }
        }
        return $booleanProperties;
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function I_can_enable_every_boolean_option()
    {
        $booleanOptionNames = $this->getBooleanOptionNames();
        foreach ($booleanOptionNames as $booleanOptionName) {
            $testsConfiguration = $this->createTestsConfiguration([$booleanOptionName => true]);
            $testedOptionGetter = StringTools::assembleGetterForName($booleanOptionName, '');
            self::assertTrue(
                $testsConfiguration->$testedOptionGetter(),
                "$testedOptionGetter() should return true in this test, is that value properly initialized in constructor?"
            );
        }
    }

    /**
     * @test
     */
    public function I_can_disable_test_of_table_of_contents(): void
    {
        $testsConfiguration = $this->createTestsConfiguration();
        self::assertTrue($testsConfiguration->hasTableOfContents(), 'Table of contents should be expected to test by default');
        $testsConfiguration = $this->createTestsConfiguration([TestsConfiguration::HAS_TABLE_OF_CONTENTS => false]);
        self::assertFalse($testsConfiguration->hasTableOfContents(), 'Test of table of contents should be disabled now');
    }

    protected function createTestsConfiguration(array $config = []): TestsConfiguration
    {
        $sutClass = static::getSutClass();

        return new $sutClass(\array_merge($this->getTestsConfigurationDefaultValues(), $config));
    }

    protected function getTestsConfigurationDefaultValues(): array
    {
        return [
            TestsConfiguration::SOME_EXPECTED_TABLE_IDS => [],
            TestsConfiguration::EXPECTED_PUBLIC_URL => 'https://www.drdplus.info',
            TestsConfiguration::EXPECTED_WEB_NAME => 'foo',
            TestsConfiguration::EXPECTED_PAGE_TITLE => 'foo',
            TestsConfiguration::EXPECTED_GOOGLE_ANALYTICS_ID => 'UA-UB-1',
        ];
    }

    protected static function getSutClass(string $sutTestClass = null, string $regexp = '~(.+)Test$~'): string
    {
        return parent::getSutClass($sutTestClass, $regexp);
    }

    /**
     * @test
     */
    public function I_can_disable_test_of_tables(): void
    {
        $testsConfiguration = $this->createTestsConfiguration();
        self::assertTrue($testsConfiguration->hasTables(), 'Tables should be expected to test by default');
        $testsConfiguration = $this->createTestsConfiguration([TestsConfiguration::HAS_TABLES => false]);
        self::assertFalse($testsConfiguration->hasTables(), 'Test of tables should be disabled now');
    }

    /**
     * @test
     */
    public function I_can_disable_expected_table_ids(): void
    {
        $testsConfiguration = $this->createTestsConfiguration([TestsConfiguration::SOME_EXPECTED_TABLE_IDS => ['foo', 'bar']]);
        self::assertSame(['foo', 'bar'], $testsConfiguration->getSomeExpectedTableIds());
    }

    /**
     * @test
     */
    public function Expected_some_table_ids_are_empty_if_no_tables_are_expected_at_all(): void
    {
        $testsConfiguration = $this->createTestsConfiguration([TestsConfiguration::SOME_EXPECTED_TABLE_IDS => ['foo', 'bar']]);
        self::assertTrue($testsConfiguration->hasTables(), 'Tables should be expected to test by default');
        self::assertSame(['foo', 'bar'], $testsConfiguration->getSomeExpectedTableIds());
        $testsConfiguration = $this->createTestsConfiguration(
            [TestsConfiguration::HAS_TABLES => false, TestsConfiguration::SOME_EXPECTED_TABLE_IDS => ['foo', 'bar']]
        );
        self::assertFalse($testsConfiguration->hasTables(), 'Test of tables should be disabled now');
        self::assertSame([], $testsConfiguration->getSomeExpectedTableIds(), 'No table IDs expected as tables tests have been disabled');
    }

    /**
     * @test
     */
    public function I_can_set_allowed_calculation_id_prefixes(): void
    {
        $testsConfiguration = $this->createTestsConfiguration();
        $originalAllowedCalculationIdPrefixes = $testsConfiguration->getAllowedCalculationIdPrefixes();
        self::assertNotEmpty($originalAllowedCalculationIdPrefixes, 'Some allowed calculation ID prefixes expected');
        $testsConfiguration = $this->createTestsConfiguration(
            [TestsConfiguration::ALLOWED_CALCULATION_ID_PREFIXES => ['Foo allowed calculation id prefix']]
        );
        self::assertSame(['Foo allowed calculation id prefix'], $testsConfiguration->getAllowedCalculationIdPrefixes());
    }

    /**
     * @test
     */
    public function I_can_not_add_allowed_calculation_id_prefix_with_lowercase_first_letter(): void
    {
        $this->expectException(\DrdPlus\Tests\RulesSkeleton\Exceptions\AllowedCalculationPrefixShouldStartByUpperLetter::class);
        $this->expectExceptionMessageMatches('~říčany u čeho chceš~');
        $this->createTestsConfiguration(
            [TestsConfiguration::ALLOWED_CALCULATION_ID_PREFIXES => ['říčany u čeho chceš']]
        );
    }

    /**
     * @test
     */
    public function I_can_not_set_allowed_calculation_id_prefixes_with_even_single_one_with_lowercase_first_letter(): void
    {
        $this->expectException(\DrdPlus\Tests\RulesSkeleton\Exceptions\AllowedCalculationPrefixShouldStartByUpperLetter::class);
        $this->expectExceptionMessageMatches('~žbrdloch~');
        $this->createTestsConfiguration(
            [TestsConfiguration::ALLOWED_CALCULATION_ID_PREFIXES => [
                'Potvora na entou',
                'Kuloár',
                'žbrdloch',
            ]]
        );
    }

    /**
     * @test
     */
    public function I_can_disable_test_of_headings(): void
    {
        $testsConfiguration = $this->createTestsConfiguration();
        self::assertTrue($testsConfiguration->hasHeadings(), 'Test of headings should be enabled by default');
        $testsConfiguration = $this->createTestsConfiguration([TestsConfiguration::HAS_HEADINGS => false]);
        self::assertFalse($testsConfiguration->hasHeadings(), 'Can not disable test of headings');
    }

    protected function getNonExistingSettersToSkip(): array
    {
        return ['setLocalUrl', 'setPublicUrl'];
    }

    /**
     * @test
     */
    public function I_can_set_and_get_local_and_public_url(): void
    {
        $testsConfiguration = $this->createTestsConfiguration([TestsConfiguration::EXPECTED_PUBLIC_URL => 'https://drdplus.info']);
        self::assertSame('http://drdplus.loc', $testsConfiguration->getLocalUrl());
        self::assertSame('https://drdplus.info', $testsConfiguration->getExpectedPublicUrl());
    }

    /**
     * @test
     */
    public function I_can_not_create_it_with_invalid_public_url(): void
    {
        $this->expectException(\DrdPlus\Tests\RulesSkeleton\Exceptions\InvalidPublicUrl::class);
        $this->expectExceptionMessageMatches('~not valid~');
        $this->createTestsConfiguration([TestsConfiguration::EXPECTED_PUBLIC_URL => 'example.com']); // missing protocol
    }

    /**
     * @test
     */
    public function I_can_not_create_it_with_public_url_without_https(): void
    {
        $this->expectException(\DrdPlus\Tests\RulesSkeleton\Exceptions\PublicUrlShouldUseHttps::class);
        $this->expectExceptionMessageMatches('~HTTPS~');
        $this->createTestsConfiguration([TestsConfiguration::EXPECTED_PUBLIC_URL => 'http://example.com']);
    }

    /**
     * @test
     */
    public function I_will_get_expected_licence_by_access_by_default(): void
    {
        $testsConfiguration = $this->createTestsConfiguration();
        self::assertTrue($testsConfiguration->hasProtectedAccess());
        self::assertSame('proprietary', $testsConfiguration->getExpectedLicence(), 'Expected proprietary licence for protected access');
        $testsConfiguration = $this->createTestsConfiguration([TestsConfiguration::HAS_PROTECTED_ACCESS => false]);
        self::assertFalse($testsConfiguration->hasProtectedAccess());
        self::assertSame('MIT', $testsConfiguration->getExpectedLicence(), 'Expected MIT licence for free access');
        $testsConfiguration = $this->createTestsConfiguration([TestsConfiguration::EXPECTED_LICENCE => 'foo']);
        self::assertSame('foo', $testsConfiguration->getExpectedLicence());
    }

    /**
     * @test
     */
    public function I_can_set_too_short_failure_names(): void
    {
        $testsConfiguration = $this->createTestsConfiguration();
        self::assertSame(['nevšiml si'], $testsConfiguration->getTooShortFailureNames());
        $testsConfiguration = $this->createTestsConfiguration([TestsConfiguration::TOO_SHORT_FAILURE_NAMES => ['foo']]);
        self::assertSame(['foo'], $testsConfiguration->getTooShortFailureNames());
    }

    /**
     * @test
     */
    public function I_can_set_every_too_short_failure_name_just_once(): void
    {
        $testsConfiguration = $this->createTestsConfiguration([
            TestsConfiguration::TOO_SHORT_FAILURE_NAMES => ['foo', 'foo', 'bar', 'bar', 'bar', 'foo', 'baz'],
        ]);
        self::assertSame(['foo', 'bar', 'baz'], $testsConfiguration->getTooShortFailureNames());
    }

    /**
     * @test
     */
    public function I_can_set_too_short_success_names(): void
    {
        $testsConfiguration = $this->createTestsConfiguration();
        self::assertSame(['všiml si'], $testsConfiguration->getTooShortSuccessNames());
        $testsConfiguration = $this->createTestsConfiguration([TestsConfiguration::TOO_SHORT_SUCCESS_NAMES => ['foo']]);
        self::assertSame(['foo'], $testsConfiguration->getTooShortSuccessNames());
    }

    /**
     * @test
     */
    public function I_can_set_every_too_short_success_name_just_once(): void
    {
        $testsConfiguration = $this->createTestsConfiguration([
            TestsConfiguration::TOO_SHORT_SUCCESS_NAMES => ['foo', 'foo', 'bar', 'bar', 'bar', 'foo', 'baz'],
        ]);
        self::assertSame(['foo', 'bar', 'baz'], $testsConfiguration->getTooShortSuccessNames());
    }

    /**
     * @test
     */
    public function I_can_set_too_short_result_names(): void
    {
        $testsConfiguration = $this->createTestsConfiguration();
        self::assertSame(['Bonus', 'Postih'], $testsConfiguration->getTooShortResultNames());
        $testsConfiguration = $this->createTestsConfiguration([TestsConfiguration::TOO_SHORT_RESULT_NAMES => ['foo']]);
        self::assertSame(['foo'], $testsConfiguration->getTooShortResultNames());
    }

    /**
     * @test
     */
    public function I_can_set_every_too_short_result_name_just_once(): void
    {
        $testsConfiguration = $this->createTestsConfiguration([
            TestsConfiguration::TOO_SHORT_RESULT_NAMES => ['foo', 'foo', 'bar', 'bar', 'bar', 'foo', 'baz'],
        ]);
        self::assertSame(['foo', 'bar', 'baz'], $testsConfiguration->getTooShortResultNames());
    }

    /**
     * @test
     */
    public function I_am_stopped_if_public_url_is_missing(): void
    {
        $this->expectException(\DrdPlus\Tests\RulesSkeleton\Exceptions\InvalidPublicUrl::class);
        $this->createTestsConfiguration([TestsConfiguration::EXPECTED_PUBLIC_URL => null]);
    }

    /**
     * @test
     * @dataProvider provideStrictBooleanConfiguration
     * @param string $directive
     * @param $value
     */
    public function Skeleton_boolean_tests_configuration_is_strict(string $directive, $value)
    {
        if (!$this->isRulesSkeletonChecked()) {
            self::assertTrue(true, 'SKeleton already checked this for you');
            return;
        }
        $getter = StringTools::assembleGetterForName($directive, '');
        self::assertSame($value, $this->getTestsConfiguration()->$getter());
    }

    public function provideStrictBooleanConfiguration(): array
    {
        return [
            TestsConfiguration::HAS_LOCAL_LINKS => [TestsConfiguration::HAS_LOCAL_LINKS, true],
            TestsConfiguration::HAS_EXTERNAL_ANCHORS_WITH_HASHES => [TestsConfiguration::HAS_EXTERNAL_ANCHORS_WITH_HASHES, true],
            TestsConfiguration::HAS_IDS => [TestsConfiguration::HAS_IDS, true],
            TestsConfiguration::HAS_CALCULATIONS => [TestsConfiguration::HAS_CALCULATIONS, true],
            TestsConfiguration::HAS_LINKS_TO_ALTAR => [TestsConfiguration::HAS_LINKS_TO_ALTAR, true],
            TestsConfiguration::CAN_BE_BOUGHT_ON_ESHOP => [TestsConfiguration::CAN_BE_BOUGHT_ON_ESHOP, true],
            TestsConfiguration::HAS_LINK_TO_SINGLE_JOURNAL => [TestsConfiguration::HAS_LINK_TO_SINGLE_JOURNAL, true],
            TestsConfiguration::HAS_LINKS_TO_JOURNALS => [TestsConfiguration::HAS_LINKS_TO_JOURNALS, true],
            TestsConfiguration::HAS_BUTTONS => [TestsConfiguration::HAS_BUTTONS, true],
            TestsConfiguration::HAS_MARKED_CONTENT => [TestsConfiguration::HAS_MARKED_CONTENT, true],
            TestsConfiguration::HAS_TABLES => [TestsConfiguration::HAS_TABLES, true],
            TestsConfiguration::HAS_PROTECTED_ACCESS => [TestsConfiguration::HAS_PROTECTED_ACCESS, true],
            TestsConfiguration::HAS_NOTES => [TestsConfiguration::HAS_NOTES, true],
            TestsConfiguration::HAS_HEADINGS => [TestsConfiguration::HAS_HEADINGS, true],
            TestsConfiguration::HAS_AUTHORS => [TestsConfiguration::HAS_AUTHORS, true],
            TestsConfiguration::HAS_SHOWN_HOME_BUTTON => [TestsConfiguration::HAS_SHOWN_HOME_BUTTON, false],
            TestsConfiguration::HAS_SHOWN_HOME_BUTTON_ON_HOMEPAGE => [TestsConfiguration::HAS_SHOWN_HOME_BUTTON_ON_HOMEPAGE, true],
            TestsConfiguration::HAS_SHOWN_HOME_BUTTON_ON_ROUTES => [TestsConfiguration::HAS_SHOWN_HOME_BUTTON_ON_ROUTES, true],
        ];
    }

    /**
     * @test
     * @dataProvider provideStrictArrayConfiguration
     * @param string $directive
     * @param bool $hasContent
     */
    public function Skeleton_array_tests_configuration_is_strict(string $directive, bool $hasContent)
    {
        if (!$this->isSkeletonChecked()) {
            self::assertTrue(true, 'SKeleton already checked this for you');
            return;
        }
        $getter = StringTools::assembleGetterForName($directive, 'get');
        if ($hasContent) {
            self::assertNotEmpty($this->getTestsConfiguration()->$getter());
        } else {
            self::assertEmpty($this->getTestsConfiguration()->$getter());
        }
    }

    public function provideStrictArrayConfiguration(): array
    {
        return [
            TestsConfiguration::TOO_SHORT_RESULT_NAMES => [TestsConfiguration::TOO_SHORT_RESULT_NAMES, true],
            TestsConfiguration::TOO_SHORT_FAILURE_NAMES => [TestsConfiguration::TOO_SHORT_FAILURE_NAMES, true],
            TestsConfiguration::TOO_SHORT_SUCCESS_NAMES => [TestsConfiguration::TOO_SHORT_SUCCESS_NAMES, true],
            TestsConfiguration::SOME_EXPECTED_TABLE_IDS => [TestsConfiguration::SOME_EXPECTED_TABLE_IDS, true],
        ];
    }
}