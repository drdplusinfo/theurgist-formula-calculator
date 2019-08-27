<?php declare(strict_types=1);

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
    public const SHOW_HOME_BUTTON_ON_HOMEPAGE = 'show_home_button_on_homepage';
    public const SHOW_HOME_BUTTON_ON_ROUTES = 'show_home_button_on_routes';
    public const HOME_BUTTON_TARGET = 'home_button_target';
    public const NAME = 'name';
    public const TITLE_SMILEY = 'title_smiley';
    public const PROTECTED_ACCESS = 'protected_access';
    public const ESHOP_URL = 'eshop_url';
    public const FAVICON = 'favicon';
    // google
    public const GOOGLE = 'google';
    public const ANALYTICS_ID = 'analytics_id';
    // application
    public const APPLICATION = 'application';
    public const YAML_FILE_WITH_ROUTES = 'yaml_file_with_routes';
    public const DEFAULT_YAML_FILE_WITH_ROUTES = 'default_yaml_file_with_routes';

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
        $this->guardSetShowHomeButtonOnHomepage($settings);
        $this->guardSetShowHomeButtonOnRoutes($settings);
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
                sprintf(
                    'Expected something like UA-121206931-1 in configuration %s.%s, got %s',
                    static::GOOGLE,
                    static::ANALYTICS_ID,
                    $settings[static::GOOGLE][static::ANALYTICS_ID] ?? 'nothing'
                )
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
                sprintf(
                    'Expected explicitly defined menu position fix to true or false in configuration %s.%s, got nothing',
                    static::WEB,
                    static::MENU_POSITION_FIXED
                )
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
                sprintf(
                    'Expected some web name in configuration %s.%s',
                    static::WEB,
                    static::NAME
                )
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
                sprintf(
                    'Title smiley should be set in configuration %s.%s, even if just an empty string',
                    static::WEB,
                    static::TITLE_SMILEY
                )
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
                sprintf(
                    'Given e-shop URL is not valid, expected some URL in configuration %s.%s, got %s',
                    static::WEB,
                    static::ESHOP_URL,
                    $settings[static::WEB][static::ESHOP_URL] ?? 'nothing'
                )
            );
        }
    }

    protected function guardSetProtectedAccess(array $settings): void
    {
        if (($settings[static::WEB][static::PROTECTED_ACCESS] ?? null) === null) {
            throw new Exceptions\MissingProtectedAccessConfiguration(
                sprintf(
                    'Configuration if web has protected access is missing in configuration %s.%s',
                    static::WEB,
                    static::PROTECTED_ACCESS
                )
            );
        }
    }

    protected function guardSetShowHomeButton(array $settings): void
    {
        if (($settings[static::WEB][static::SHOW_HOME_BUTTON] ?? null) === null
            && (($settings[static::WEB][static::SHOW_HOME_BUTTON_ON_HOMEPAGE] ?? null) === null
                || ($settings[static::WEB][static::SHOW_HOME_BUTTON_ON_ROUTES] ?? null) === null
            )
        ) {
            throw new Exceptions\MissingShownHomeButtonConfiguration(
                sprintf(
                    'Configuration if home button should be shown is missing in configuration %s.%s',
                    static::WEB,
                    static::SHOW_HOME_BUTTON
                )
            );
        }
    }

    protected function guardSetShowHomeButtonOnHomepage(array $settings): void
    {
        if (($settings[static::WEB][static::SHOW_HOME_BUTTON] ?? null) === null
            && ($settings[static::WEB][static::SHOW_HOME_BUTTON_ON_HOMEPAGE] ?? null) === null
        ) {
            throw new Exceptions\MissingShownHomeButtonOnHomepageConfiguration(
                sprintf(
                    'Configuration if home button should be shown on homepage is missing in configuration %s.%s',
                    static::WEB,
                    static::SHOW_HOME_BUTTON_ON_HOMEPAGE
                )
            );
        }
    }

    protected function guardSetShowHomeButtonOnRoutes(array $settings): void
    {
        if (($settings[static::WEB][static::SHOW_HOME_BUTTON] ?? null) === null
            && ($settings[static::WEB][static::SHOW_HOME_BUTTON_ON_ROUTES] ?? null) === null
        ) {
            throw new Exceptions\MissingShownHomeButtonOnRoutesConfiguration(
                sprintf(
                    'Configuration if home button should be shown on routes is missing in configuration %s.%s',
                    static::WEB,
                    static::SHOW_HOME_BUTTON_ON_ROUTES
                )
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

    /**
     * @return bool
     * @deprecated
     */
    public function isShowHomeButton(): bool
    {
        return ($this->getSettings()[static::WEB][static::SHOW_HOME_BUTTON] ?? false)
            || (($this->getSettings()[static::WEB][static::SHOW_HOME_BUTTON_ON_HOMEPAGE] ?? false)
                && ($this->getSettings()[static::WEB][static::SHOW_HOME_BUTTON_ON_ROUTES] ?? false)
            );
    }

    public function isShowHomeButtonOnHomepage(): bool
    {
        return ($this->getSettings()[static::WEB][static::SHOW_HOME_BUTTON_ON_HOMEPAGE] ?? false) || $this->isShowHomeButton();
    }

    public function isShowHomeButtonOnRoutes(): bool
    {
        return ($this->getSettings()[static::WEB][static::SHOW_HOME_BUTTON_ON_ROUTES] ?? false) || $this->isShowHomeButton();
    }

    public function getHomeButtonTarget(): string
    {
        return $this->getSettings()[static::WEB][static::HOME_BUTTON_TARGET] ?? 'https://www.drdplus.info';
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

    public function getYamlFileWithRoutes(): string
    {
        return $this->getSettings()[static::APPLICATION][static::YAML_FILE_WITH_ROUTES] ?? '';
    }

    public function getDefaultYamlFileWithRoutes(): string
    {
        return $this->getSettings()[static::APPLICATION][static::DEFAULT_YAML_FILE_WITH_ROUTES] ?? 'routes.yml';
    }
}