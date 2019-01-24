<?php
declare(strict_types=1);

namespace DrdPlus\RulesSkeleton;

use Granam\Strict\Object\StrictObject;
use Granam\YamlReader\YamlFileReader;

class Configuration extends StrictObject
{
    public const CONFIG_LOCAL_YML = 'config.local.yml';
    public const CONFIG_DISTRIBUTION_YML = 'config.distribution.yml';

    public static function createFromYml(Dirs $dirs): Configuration
    {
        $globalConfig = new YamlFileReader($dirs->getProjectRoot() . '/' . static::CONFIG_DISTRIBUTION_YML);
        $config = $globalConfig->getValues();
        $localConfigFile = $dirs->getProjectRoot() . '/' . static::CONFIG_LOCAL_YML;
        if (\file_exists($localConfigFile)) {
            $localConfig = new YamlFileReader($dirs->getProjectRoot() . '/' . static::CONFIG_LOCAL_YML);
            $config = \array_replace_recursive($config, $localConfig->getValues());
        }

        return new static($dirs, $config);
    }

    // web
    public const WEB = 'web';
    public const MENU_POSITION_FIXED = 'menu_position_fixed';
    public const SHOW_HOME_BUTTON = 'show_home_button';
    public const NAME = 'name';
    public const TITLE_SMILEY = 'title_smiley';
    public const PROTECTED_ACCESS = 'protected_access';
    public const ESHOP_URL = 'eshop_url';
    public const FAVICON = 'favicon';
    // google
    public const GOOGLE = 'google';
    public const ANALYTICS_ID = 'analytics_id';

    /** @var Dirs */
    private $dirs;
    /** @var array */
    private $settings;

    /**
     * @param Dirs $dirs
     * @param array $settings
     */
    public function __construct(Dirs $dirs, array $settings)
    {
        $this->dirs = $dirs;
        $this->guardValidGoogleAnalyticsId($settings);
        $this->guardSetIfUseFixedMenuPosition($settings);
        $this->guardNonEmptyWebName($settings);
        $this->guardSetTitleSmiley($settings);
        $this->guardValidEshopUrl($settings);
        $this->guardSetProtectedAccess($settings);
        $this->guardSetShowHomeButton($settings);
        $this->guardValidFaviconUrl($settings);
        $this->settings = $settings;
    }

    /**
     * @param array $settings
     * @throws \DrdPlus\RulesSkeleton\Exceptions\InvalidGoogleAnalyticsId
     */
    protected function guardValidGoogleAnalyticsId(array $settings): void
    {
        if (!\preg_match('~^UA-121206931-\d+$~', $settings[static::GOOGLE][static::ANALYTICS_ID] ?? '')) {
            throw new Exceptions\InvalidGoogleAnalyticsId(
                'Expected something like UA-121206931-1 in configuration google.analytics_id, got ' . ($settings[static::GOOGLE][static::ANALYTICS_ID] ?? 'nothing')
            );
        }
    }

    /**
     * @param array $settings
     * @throws \DrdPlus\RulesSkeleton\Exceptions\InvalidMenuPosition
     */
    protected function guardSetIfUseFixedMenuPosition(array $settings): void
    {
        if (($settings[static::WEB][static::MENU_POSITION_FIXED] ?? null) === null) {
            throw new Exceptions\InvalidMenuPosition(
                'Expected explicitly defined menu position fix to true or false in configuration web.menu_position_fixed, got nothing'
            );
        }
    }

    /**
     * @param array $settings
     * @throws \DrdPlus\RulesSkeleton\Exceptions\MissingWebName
     */
    protected function guardNonEmptyWebName(array $settings): void
    {
        if (($settings[static::WEB][static::NAME] ?? '') === '') {
            throw new Exceptions\MissingWebName(
                'Expected some web name in configuration web.name'
            );
        }
    }

    /**
     * @param array $settings
     * @throws \DrdPlus\RulesSkeleton\Exceptions\TitleSmileyIsNotSet
     */
    protected function guardSetTitleSmiley(array $settings): void
    {
        if (!\array_key_exists(static::TITLE_SMILEY, $settings[static::WEB])) {
            throw new Exceptions\TitleSmileyIsNotSet(
                'Title smiley should be set in configuration web.title_smiley, even if just an empty string'
            );
        }
    }

    /**
     * @param array $settings
     * @throws \DrdPlus\RulesSkeleton\Exceptions\InvalidEshopUrl
     */
    protected function guardValidEshopUrl(array $settings): void
    {
        if (!\filter_var($settings[static::WEB][static::ESHOP_URL] ?? '', FILTER_VALIDATE_URL)) {
            throw new Exceptions\InvalidEshopUrl(
                'Given e-shop URL is not valid, expected some URL in configuration '
                . static::WEB . ': ' . static::ESHOP_URL . ', got ' . ($settings[static::WEB][static::ESHOP_URL] ?? 'nothing')
            );
        }
    }

    protected function guardSetProtectedAccess(array $settings): void
    {
        if (($settings[static::WEB][static::PROTECTED_ACCESS] ?? null) === null) {
            throw new Exceptions\MissingProtectedAccessConfiguration(
                'Configuration if web has protected access is missing in configuration '
                . static::WEB . ': ' . static::PROTECTED_ACCESS
            );
        }
    }

    protected function guardSetShowHomeButton(array $settings): void
    {
        if (($settings[static::WEB][static::SHOW_HOME_BUTTON] ?? null) === null) {
            throw new Exceptions\MissingShownHomeButtonConfiguration(
                'Configuration if home button should be shown is missing in configuration '
                . static::WEB . ': ' . static::SHOW_HOME_BUTTON
            );
        }
    }

    protected function guardValidFaviconUrl(array $settings): void
    {
        $favicon = $settings[static::WEB][static::FAVICON] ?? null;
        if ($favicon === null) {
            return;
        }
        if (!\filter_var($favicon, \ FILTER_VALIDATE_URL)
            && !\file_exists($this->getDirs()->getProjectRoot() . '/' . \ltrim($favicon, '/'))
        ) {
            throw new Exceptions\GivenFaviconHasNotBeenFound("Favicon $favicon is not an URL neither readable file");
        }
    }

    public function getDirs(): Dirs
    {
        return $this->dirs;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    public function getGoogleAnalyticsId(): string
    {
        return $this->getSettings()[static::GOOGLE][static::ANALYTICS_ID];
    }

    public function isMenuPositionFixed(): bool
    {
        return (bool)$this->getSettings()[static::WEB][static::MENU_POSITION_FIXED];
    }

    public function isShowHomeButton(): bool
    {
        return (bool)$this->getSettings()[static::WEB][static::SHOW_HOME_BUTTON];
    }

    public function getWebName(): string
    {
        return $this->getSettings()[static::WEB][static::NAME];
    }

    public function getTitleSmiley(): string
    {
        return (string)$this->getSettings()[static::WEB][static::TITLE_SMILEY];
    }

    public function hasProtectedAccess(): bool
    {
        return (bool)$this->getSettings()[self::WEB][self::PROTECTED_ACCESS];
    }

    public function getEshopUrl(): string
    {
        return $this->getSettings()[self::WEB][self::ESHOP_URL];
    }

    public function getFavicon(): string
    {
        return $this->getSettings()[static::WEB][static::FAVICON] ?? '';
    }
}