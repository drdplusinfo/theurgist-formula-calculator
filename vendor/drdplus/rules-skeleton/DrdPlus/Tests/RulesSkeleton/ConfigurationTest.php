<?php
declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\Configuration;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;
use Granam\String\StringTools;

class ConfigurationTest extends AbstractContentTest
{
    private $createdYamlTempFiles = [];

    /**
     * @test
     */
    public function I_can_use_both_config_distribution_as_well_as_local_yaml_files(): void
    {
        if ($this->isSkeletonChecked()) {
            self::assertFileExists(
                $this->getProjectRoot() . '/' . Configuration::CONFIG_LOCAL_YML,
                'Local configuration expected on skeleton for testing purpose'
            );
        }
        self::assertFileExists($this->getProjectRoot() . '/' . Configuration::CONFIG_DISTRIBUTION_YML);
    }

    /**
     * @test
     * @dataProvider provideCompleteLocalAndDistributionYamlContent
     * @param array $localYamlContent
     * @param array $distributionYamlContent
     * @param array $expectedYamlContent
     */
    public function I_can_create_it_from_yaml_files(array $localYamlContent, array $distributionYamlContent, array $expectedYamlContent): void
    {
        $yamlTestingDir = $this->getYamlTestingDir();
        $this->createYamlLocalConfig($localYamlContent, $yamlTestingDir);
        $this->createYamlDistributionConfig($distributionYamlContent, $yamlTestingDir);
        $configuration = Configuration::createFromYml($dirs = $this->createDirs($yamlTestingDir));
        self::assertSame($expectedYamlContent, $configuration->getSettings());
        self::assertSame($expectedYamlContent[Configuration::GOOGLE][Configuration::ANALYTICS_ID], $configuration->getGoogleAnalyticsId());
        self::assertSame($dirs, $configuration->getDirs());
    }

    protected function getYamlTestingDir(): string
    {
        $yamlTestingDir = \sys_get_temp_dir() . '/' . \uniqid(StringTools::getClassBaseName(static::class), true);
        self::assertTrue(\mkdir($yamlTestingDir), 'Testing temporary dir can not be created: ' . $yamlTestingDir);

        return $yamlTestingDir;
    }

    protected function createYamlLocalConfig(array $data, string $yamlTestingDir): string
    {
        $localYamlConfig = $yamlTestingDir . '/' . Configuration::CONFIG_LOCAL_YML;
        $this->createYamlFile($data, $localYamlConfig);
        $this->createdYamlTempFiles[] = $localYamlConfig;

        return $localYamlConfig;
    }

    private function createYamlFile(array $data, string $file): void
    {
        self::assertTrue(\yaml_emit_file($file, $data), 'Yaml file has not been created: ' . $file);
    }

    protected function createYamlDistributionConfig(array $data, string $yamlTestingDir): string
    {
        $distributionYamlConfig = $yamlTestingDir . '/' . Configuration::CONFIG_DISTRIBUTION_YML;
        $this->createYamlFile($data, $distributionYamlConfig);
        $this->createdYamlTempFiles[] = $distributionYamlConfig;

        return $distributionYamlConfig;
    }

    public function __destruct()
    {
        foreach ($this->createdYamlTempFiles as $createdYamlTempFile) {
            \unlink($createdYamlTempFile);
        }
    }

    public function provideCompleteLocalAndDistributionYamlContent(): array
    {
        $completeYamlContent = $this->getSomeCompleteSettings();
        $limitedWebSection = $completeYamlContent;
        $changedCompleteYamlContent = $completeYamlContent;

        return [
            [$completeYamlContent, [], $completeYamlContent],
            [$limitedWebSection, $completeYamlContent, $changedCompleteYamlContent],
            [$completeYamlContent, $limitedWebSection, $completeYamlContent],
        ];
    }

    protected function getSomeCompleteSettings(): array
    {
        return [
            Configuration::WEB => [
                Configuration::MENU_POSITION_FIXED => false,
                Configuration::SHOW_HOME_BUTTON => true,
                Configuration::NAME => 'Foo',
                Configuration::TITLE_SMILEY => '',
                Configuration::PROTECTED_ACCESS => true,
                Configuration::ESHOP_URL => 'https://example.com',
            ],
            Configuration::GOOGLE => [Configuration::ANALYTICS_ID => 'UA-121206931-999'],
        ];
    }

    public function Google_analytics_id_is_unique(): void
    {
        if ($this->isRulesSkeletonChecked()) {
            self::assertSame('UA-121206931-0', $this->getConfiguration()->getGoogleAnalyticsId());
        } else {
            self::assertNotSame(
                'UA-121206931-1',
                $this->getConfiguration()->getGoogleAnalyticsId(),
                'Some valid Google analytics should be used'
            );
        }
    }

    /**
     * @test
     * @expectedException \DrdPlus\RulesSkeleton\Exceptions\InvalidGoogleAnalyticsId
     * @expectedExceptionMessageRegExp ~GoogleItself~
     */
    public function I_can_not_create_it_with_invalid_google_analytics_id(): void
    {
        $completeSettings = $this->getSomeCompleteSettings();
        $completeSettings[Configuration::GOOGLE][Configuration::ANALYTICS_ID] = 'GoogleItself';
        new Configuration($this->getDirs(), $completeSettings);
    }

    /**
     * @test
     * @expectedException \DrdPlus\RulesSkeleton\Exceptions\InvalidMenuPosition
     */
    public function I_can_not_create_it_without_defining_if_menu_should_be_fixed(): void
    {
        $completeSettings = $this->getSomeCompleteSettings();
        unset($completeSettings[Configuration::WEB][Configuration::MENU_POSITION_FIXED]);
        new Configuration($this->getDirs(), $completeSettings);
    }

    /**
     * @test
     * @expectedException \DrdPlus\RulesSkeleton\Exceptions\MissingShownHomeButtonConfiguration
     */
    public function I_can_not_create_it_without_defining_if_show_home_button(): void
    {
        $completeSettings = $this->getSomeCompleteSettings();
        unset($completeSettings[Configuration::WEB][Configuration::SHOW_HOME_BUTTON]);
        new Configuration($this->getDirs(), $completeSettings);
    }

    /**
     * @test
     * @expectedException \DrdPlus\RulesSkeleton\Exceptions\MissingWebName
     */
    public function I_can_not_create_it_without_web_name(): void
    {
        $completeSettings = $this->getSomeCompleteSettings();
        $completeSettings[Configuration::WEB][Configuration::NAME] = '';
        new Configuration($this->getDirs(), $completeSettings);
    }

    /**
     * @test
     * @expectedException \DrdPlus\RulesSkeleton\Exceptions\TitleSmileyIsNotSet
     */
    public function I_can_not_create_it_without_set_title_smiley(): void
    {
        $completeSettings = $this->getSomeCompleteSettings();
        unset($completeSettings[Configuration::WEB][Configuration::TITLE_SMILEY]);
        new Configuration($this->getDirs(), $completeSettings);
    }

    /**
     * @test
     */
    public function I_can_create_it_with_title_smiley_as_null(): void
    {
        $completeSettings = $this->getSomeCompleteSettings();
        $completeSettings[Configuration::WEB][Configuration::TITLE_SMILEY] = null;
        $configuration = new Configuration($this->getDirs(), $completeSettings);
        self::assertSame('', $configuration->getTitleSmiley());
    }
}