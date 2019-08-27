<?php declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\Tests\RulesSkeleton\TestsConfiguration;

class TestsConfigurationTest extends \DrdPlus\Tests\RulesSkeleton\TestsConfigurationTest
{
    use Partials\CalculatorContentTestTrait;

    public function provideStrictBooleanConfiguration(): array
    {
        return array_replace(
            parent::provideStrictBooleanConfiguration(),
            [
                TestsConfiguration::CAN_BE_BOUGHT_ON_ESHOP => [TestsConfiguration::CAN_BE_BOUGHT_ON_ESHOP, false],
                TestsConfiguration::HAS_LINK_TO_SINGLE_JOURNAL => [TestsConfiguration::HAS_LINK_TO_SINGLE_JOURNAL, false],
                TestsConfiguration::HAS_LINKS_TO_JOURNALS => [TestsConfiguration::HAS_LINKS_TO_JOURNALS, false],
                TestsConfiguration::HAS_AUTHORS => [TestsConfiguration::HAS_AUTHORS, false],
                TestsConfiguration::HAS_PROTECTED_ACCESS => [TestsConfiguration::HAS_PROTECTED_ACCESS, false],
                TestsConfiguration::HAS_TABLES => [TestsConfiguration::HAS_TABLES, false],
                TestsConfiguration::HAS_NOTES => [TestsConfiguration::HAS_NOTES, false],
            ]
        );
    }

    public function provideStrictArrayConfiguration(): array
    {
        return array_replace(
            parent::provideStrictArrayConfiguration(),
            [
                TestsConfiguration::TOO_SHORT_RESULT_NAMES => [TestsConfiguration::TOO_SHORT_RESULT_NAMES, false],
                TestsConfiguration::TOO_SHORT_FAILURE_NAMES => [TestsConfiguration::TOO_SHORT_FAILURE_NAMES, false],
                TestsConfiguration::TOO_SHORT_SUCCESS_NAMES => [TestsConfiguration::TOO_SHORT_SUCCESS_NAMES, false],
                TestsConfiguration::SOME_EXPECTED_TABLE_IDS => [TestsConfiguration::SOME_EXPECTED_TABLE_IDS, false],
            ]
        );
    }
}