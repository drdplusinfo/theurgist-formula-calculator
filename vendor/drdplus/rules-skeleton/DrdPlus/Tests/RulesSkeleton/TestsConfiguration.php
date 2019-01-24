<?php
declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\Tests\RulesSkeleton\Exceptions\InvalidUrl;
use DrdPlus\Tests\RulesSkeleton\Partials\TestsConfigurationReader;
use Granam\Strict\Object\StrictObject;
use Granam\YamlReader\YamlFileReader;

class TestsConfiguration extends StrictObject implements TestsConfigurationReader
{
    public const LICENCE_BY_ACCESS = '*by access*';
    public const LICENCE_MIT = 'MIT';
    public const LICENCE_PROPRIETARY = 'proprietary';

    public const HAS_TABLES = 'has_tables';
    public const SOME_EXPECTED_TABLE_IDS = 'some_expected_table_ids';
    public const HAS_TABLE_OF_CONTENTS = 'has_table_of_contents';
    public const HAS_HEADINGS = 'has_headings';
    public const HAS_AUTHORS = 'has_authors';

    public const PUBLIC_URL = 'public_url';
    public const HAS_EXTERNAL_ANCHORS_WITH_HASHES = 'has_external_anchors_with_hashes';
    public const HAS_CUSTOM_BODY_CONTENT = 'has_custom_body_content';
    public const HAS_NOTES = 'has_notes';
    public const HAS_IDS = 'has_ids';
    public const HAS_LOCAL_LINKS = 'has_local_links';
    public const HAS_LINKS_TO_ALTAR = 'has_links_to_altar';
    public const EXPECTED_WEB_NAME = 'expected_web_name';
    public const ALLOWED_CALCULATION_ID_PREFIXES = 'allowed_calculation_id_prefixes';
    public const EXPECTED_PAGE_TITLE = 'expected_page_title';
    public const EXPECTED_GOOGLE_ANALYTICS_ID = 'expected_google_analytics_id';
    public const HAS_PROTECTED_ACCESS = 'has_protected_access';
    public const CAN_BE_BOUGHT_ON_ESHOP = 'can_be_bought_on_eshop';
    public const HAS_DEBUG_CONTACTS = 'has_debug_contacts';
    public const EXPECTED_LICENCE = 'expected_licence';
    public const HAS_CHARACTER_SHEET = 'has_character_sheet';
    public const HAS_LINKS_TO_JOURNALS = 'has_links_to_journals';
    public const HAS_LINK_TO_SINGLE_JOURNAL = 'has_link_to_single_journal';
    public const TOO_SHORT_FAILURE_NAMES = 'too_short_failure_names';
    public const TOO_SHORT_SUCCESS_NAMES = 'too_short_success_names';
    public const TOO_SHORT_RESULT_NAMES = 'too_short_result_names';

    public static function createFromYaml(string $yamlConfigFile)
    {
        return new static((new YamlFileReader($yamlConfigFile))->getValues());
    }

    // every setting SHOULD be strict (expecting instead of ignoring)

    /** @var bool */
    private $hasTables = true;
    /** @var array|string[] */
    private $someExpectedTableIds = [];
    /** @var bool */
    private $hasTableOfContents = true;
    /** @var bool */
    private $hasHeadings = true;
    /** @var bool */
    private $hasAuthors = true;
    /** @var string */
    private $localUrl;
    /** @var bool */
    private $hasExternalAnchorsWithHashes = true;
    /** @var bool */
    private $hasCustomBodyContent = true;
    /** @var bool */
    private $hasNotes = true;
    /** @var bool */
    private $hasIds = true;
    /** @var bool */
    private $hasLocalLinks = true;
    /** @var bool */
    private $hasLinksToAltar = true;
    /** @var string */
    private $expectedWebName;
    /** @var string */
    private $expectedPageTitle;
    /** @var string */
    private $expectedGoogleAnalyticsId = 'UA-121206931-1';
    /** @var array|string[] */
    private $allowedCalculationIdPrefixes = ['Hod proti', 'Hod na', 'Výpočet'];
    /** @var string */
    private $publicUrl;
    /** @var bool */
    private $hasProtectedAccess = true;
    /** @var bool */
    private $canBeBoughtOnEshop = true;
    /** @var bool */
    private $hasCharacterSheet = true;
    /** @var bool */
    private $hasLinksToJournals = true;
    /** @var bool */
    private $hasLinkToSingleJournal = true;
    /** @var bool */
    private $hasDebugContacts = true;
    /** @var string */
    private $expectedLicence = self::LICENCE_BY_ACCESS;
    /** @var array|string[] */
    private $tooShortFailureNames = ['nevšiml si'];
    /** @var array|string[] */
    private $tooShortSuccessNames = ['všiml si'];
    /** @var array|string[] */
    private $tooShortResultNames = ['Bonus', 'Postih'];

    /**
     * @param array $values
     * @throws \DrdPlus\Tests\RulesSkeleton\Exceptions\InvalidLocalUrl
     * @throws \DrdPlus\Tests\RulesSkeleton\Exceptions\InvalidPublicUrl
     * @throws \DrdPlus\Tests\RulesSkeleton\Exceptions\PublicUrlShouldUseHttps
     */
    public function __construct(array $values)
    {
        $this->setHasTables($values);
        $this->setSomeExpectedTableIds($values, $this->hasTables());
        $this->setHasTableOfContents($values);
        $this->setHasHeadings($values);
        $this->setHasAuthors($values);
        $this->setPublicUrl($values);
        $this->setLocalUrl($this->publicUrl);
        $this->setHasExternalAnchorsWithHashes($values);
        $this->setHasCustomBodyContent($values);
        $this->setHasNotes($values);
        $this->setHasIds($values);
        $this->setHasLocalLinks($values);
        $this->setHasLinksToAltar($values);
        $this->setExpectedWebName($values);
        $this->setAllowedCalculationIdPrefixes($values);
        $this->setExpectedPageTitle($values);
        $this->setExpectedGoogleAnalyticsId($values);
        $this->setHasProtectedAccess($values);
        $this->setCanBeBoughtOnEshop($values);
        $this->setHasDebugContacts($values);
        $this->setExpectedLicence($values);
        $this->setHasCharacterSheet($values);
        $this->setHasLinksToJournals($values);
        $this->setHasLinkToSingleJournal($values);
        $this->setTooShortFailureNames($values);
        $this->setTooShortSuccessNames($values);
        $this->setTooShortResultNames($values);
    }

    /**
     * @param array $values
     */
    private function setHasTables(array $values): void
    {
        $this->hasTables = (bool)($values[self::HAS_TABLES] ?? $this->hasTables);
    }

    public function hasTables(): bool
    {
        return $this->hasTables;
    }

    /**
     * @param array $values
     * @param bool $hasTables
     * @throws \DrdPlus\Tests\RulesSkeleton\Exceptions\MissingSomeExpectedTableIdsInTestsConfiguration
     */
    private function setSomeExpectedTableIds(array $values, bool $hasTables): void
    {
        if (!$hasTables) {
            $this->someExpectedTableIds = [];

            return;
        }
        $someExpectedTableIds = $values[self::SOME_EXPECTED_TABLE_IDS] ?? null;
        if (!\is_array($someExpectedTableIds)) {
            throw new Exceptions\MissingSomeExpectedTableIdsInTestsConfiguration(
                "Expected some '" . self::SOME_EXPECTED_TABLE_IDS . "', got "
                . ($someExpectedTableIds === null
                    ? 'nothing'
                    : \var_export($someExpectedTableIds, true)
                )
            );
        }
        $structureOk = true;
        foreach ($someExpectedTableIds as $someExpectedTableId) {
            if (!\is_string($someExpectedTableId)) {
                $structureOk = false;
                break;
            }
        }
        if (!$structureOk) {
            throw new Exceptions\MissingSomeExpectedTableIdsInTestsConfiguration(
                "Expected flat array of strings for '" . self::SOME_EXPECTED_TABLE_IDS . "', got "
                . \var_export($someExpectedTableIds, true)
            );
        }
        $this->someExpectedTableIds = $someExpectedTableIds;
    }

    private function setHasTableOfContents(array $values): void
    {
        $this->hasTableOfContents = (bool)($values[self::HAS_TABLE_OF_CONTENTS] ?? true);
    }

    private function setHasHeadings(array $values): void
    {
        $this->hasHeadings = (bool)($values[self::HAS_HEADINGS] ?? $this->hasHeadings);
    }

    public function getSomeExpectedTableIds(): array
    {
        return $this->someExpectedTableIds;
    }

    public function hasTableOfContents(): bool
    {
        return $this->hasTableOfContents;
    }

    public function hasHeadings(): bool
    {
        return $this->hasHeadings;
    }

    private function setHasAuthors(array $values): void
    {
        $this->hasAuthors = (bool)($values[self::HAS_AUTHORS] ?? $this->hasAuthors);
    }

    public function hasAuthors(): bool
    {
        return $this->hasAuthors;
    }

    private function setPublicUrl(array $values)
    {
        $publicUrl = \trim($values[self::PUBLIC_URL] ?? '');
        try {
            $this->guardValidUrl($publicUrl);
        } catch (InvalidUrl $invalidUrl) {
            throw new Exceptions\InvalidPublicUrl(
                sprintf("Given public URL under key '%s' is not valid: '%s'", self::PUBLIC_URL, $publicUrl),
                $invalidUrl->getCode(),
                $invalidUrl
            );
        }
        if (\strpos($publicUrl, 'https://') !== 0) {
            throw new Exceptions\PublicUrlShouldUseHttps("Given public URL should use HTTPS: '$publicUrl'");
        }
        $this->publicUrl = $publicUrl;
    }

    /**
     * @param string $url
     * @throws \DrdPlus\Tests\RulesSkeleton\Exceptions\InvalidUrl
     */
    private function guardValidUrl(string $url): void
    {
        if (!\filter_var($url, \FILTER_VALIDATE_URL)) {
            throw new Exceptions\InvalidUrl("Given URL is not valid: '$url'");
        }
    }

    private function setLocalUrl(string $publicUrl)
    {
        $localUrl = HtmlHelper::turnToLocalLink($publicUrl);
        if (!$this->isLocalLinkAccessible($localUrl)) {
            throw new Exceptions\InvalidLocalUrl("Given local URL can not be reached or is not local: '$localUrl'");
        }
        $this->guardValidUrl($localUrl);
        $this->localUrl = $localUrl;
    }

    private function isLocalLinkAccessible(string $localUrl): bool
    {
        $host = \parse_url($localUrl, \PHP_URL_HOST);

        return $host !== false
            && !\filter_var($host, \FILTER_VALIDATE_IP)
            && \gethostbyname($host) === '127.0.0.1';
    }

    private function setHasExternalAnchorsWithHashes(array $values)
    {
        $this->hasExternalAnchorsWithHashes = (bool)($values[self::HAS_EXTERNAL_ANCHORS_WITH_HASHES] ?? $this->hasExternalAnchorsWithHashes);
    }

    private function setHasCustomBodyContent(array $values)
    {
        $this->hasCustomBodyContent = (bool)($values[self::HAS_CUSTOM_BODY_CONTENT] ?? $this->hasCustomBodyContent);
    }

    private function setHasNotes(array $values)
    {
        $this->hasNotes = (bool)($values[self::HAS_NOTES] ?? $this->hasNotes);
    }

    private function setHasIds(array $values)
    {
        $this->hasIds = (bool)($values[self::HAS_IDS] ?? $this->hasIds);
    }

    private function setHasLocalLinks(array $values)
    {
        $this->hasLocalLinks = (bool)($values[self::HAS_LOCAL_LINKS] ?? $this->hasLocalLinks);
    }

    private function setHasLinksToAltar(array $values)
    {
        $this->hasLinksToAltar = (bool)($values[self::HAS_LINKS_TO_ALTAR] ?? $this->hasLinksToAltar);
    }

    private function setExpectedWebName(array $values)
    {
        $expectedWebName = \trim($values[self::EXPECTED_WEB_NAME] ?? '');
        if ($expectedWebName === '') {
            throw new Exceptions\MissingExpectedWebName('Expected some web name under key ' . self::EXPECTED_WEB_NAME);
        }
        $this->expectedWebName = $expectedWebName;
    }

    private function setAllowedCalculationIdPrefixes(array $values)
    {
        if (!isset($values[self::ALLOWED_CALCULATION_ID_PREFIXES])) {
            return;
        }
        $this->allowedCalculationIdPrefixes = [];
        foreach ($values[self::ALLOWED_CALCULATION_ID_PREFIXES] as $allowedCalculationIdPrefix) {
            if (!\preg_match('~^[[:upper:]]~u', $allowedCalculationIdPrefix)) {
                throw new Exceptions\AllowedCalculationPrefixShouldStartByUpperLetter(
                    "First letter of allowed calculation prefix should be uppercase, got '$allowedCalculationIdPrefix'"
                );
            }
            $this->allowedCalculationIdPrefixes[] = $allowedCalculationIdPrefix;
        }
    }

    private function setExpectedPageTitle(array $values)
    {
        $expectedPageTitle = \trim($values[self::EXPECTED_PAGE_TITLE] ?? '');
        if ($expectedPageTitle === '') {
            throw new Exceptions\MissingExpectedPageTitle('Expected some page title under key ' . self::EXPECTED_PAGE_TITLE);
        }
        $this->expectedPageTitle = $expectedPageTitle;
    }

    private function setExpectedGoogleAnalyticsId(array $values)
    {
        $expectedGoogleAnalyticsId = \trim($values[self::EXPECTED_GOOGLE_ANALYTICS_ID] ?? '');
        if ($expectedGoogleAnalyticsId === '') {
            throw new Exceptions\MissingExpectedGoogleAnalyticsId('Expected some Google analytics ID under key ' . self::EXPECTED_GOOGLE_ANALYTICS_ID);
        }
        $this->expectedGoogleAnalyticsId = $expectedGoogleAnalyticsId;
    }

    private function setHasProtectedAccess(array $values)
    {
        $this->hasProtectedAccess = (bool)($values[self::HAS_PROTECTED_ACCESS] ?? $this->hasProtectedAccess);
    }

    private function setCanBeBoughtOnEshop(array $values)
    {
        $this->canBeBoughtOnEshop = (bool)($values[self::CAN_BE_BOUGHT_ON_ESHOP] ?? $this->canBeBoughtOnEshop);
    }

    private function setHasDebugContacts(array $values)
    {
        $this->hasDebugContacts = (bool)($values[self::HAS_DEBUG_CONTACTS] ?? $this->hasDebugContacts);
    }

    private function setExpectedLicence(array $values)
    {
        $this->expectedLicence = (string)($values[self::EXPECTED_LICENCE] ?? $this->expectedLicence);
    }

    private function setHasCharacterSheet(array $values)
    {
        $this->hasCharacterSheet = (bool)($values[self::HAS_CHARACTER_SHEET] ?? $this->hasCharacterSheet);
    }

    private function setHasLinksToJournals(array $values)
    {
        $this->hasLinksToJournals = (bool)($values[self::HAS_LINKS_TO_JOURNALS] ?? $this->hasLinksToJournals);
    }

    private function setHasLinkToSingleJournal(array $values)
    {
        $this->hasLinkToSingleJournal = (bool)($values[self::HAS_LINK_TO_SINGLE_JOURNAL] ?? $this->hasLinkToSingleJournal);
    }

    private function setTooShortFailureNames(array $values)
    {
        if (!isset($values[self::TOO_SHORT_FAILURE_NAMES])) {
            return;
        }
        $this->tooShortFailureNames = [];
        foreach ($values[self::TOO_SHORT_FAILURE_NAMES] as $tooShortFailureName) {
            if (!\in_array($tooShortFailureName, $this->tooShortFailureNames, true)) {
                $this->tooShortFailureNames[] = $tooShortFailureName;
            }
        }
    }

    private function setTooShortSuccessNames(array $values)
    {
        if (!isset($values[self::TOO_SHORT_SUCCESS_NAMES])) {
            return;
        }
        $this->tooShortSuccessNames = [];
        foreach ($values[self::TOO_SHORT_SUCCESS_NAMES] as $tooShortSuccessName) {
            if (!\in_array($tooShortSuccessName, $this->tooShortSuccessNames, true)) {
                $this->tooShortSuccessNames[] = $tooShortSuccessName;
            }
        }
    }

    private function setTooShortResultNames(array $values)
    {
        if (!isset($values[self::TOO_SHORT_RESULT_NAMES])) {
            return;
        }
        $this->tooShortResultNames = [];
        foreach ($values[self::TOO_SHORT_RESULT_NAMES] as $tooShortResultName) {
            if (!\in_array($tooShortResultName, $this->tooShortResultNames, true)) {
                $this->tooShortResultNames[] = $tooShortResultName;
            }
        }
    }

    public function getPublicUrl(): string
    {
        return $this->publicUrl;
    }

    public function getLocalUrl(): string
    {
        return $this->localUrl;
    }

    public function hasExternalAnchorsWithHashes(): bool
    {
        return $this->hasExternalAnchorsWithHashes;
    }

    public function hasCustomBodyContent(): bool
    {
        return $this->hasCustomBodyContent;
    }

    public function hasNotes(): bool
    {
        return $this->hasNotes;
    }

    public function hasIds(): bool
    {
        return $this->hasIds;
    }

    public function hasLocalLinks(): bool
    {
        return $this->hasLocalLinks;
    }

    public function hasLinksToAltar(): bool
    {
        return $this->hasLinksToAltar;
    }

    public function getExpectedWebName(): string
    {
        return $this->expectedWebName;
    }

    public function getExpectedPageTitle(): string
    {
        return $this->expectedPageTitle;
    }

    public function getExpectedGoogleAnalyticsId(): string
    {
        return $this->expectedGoogleAnalyticsId;
    }

    /** @return array|string[] */
    public function getAllowedCalculationIdPrefixes(): array
    {
        return $this->allowedCalculationIdPrefixes;
    }

    public function hasProtectedAccess(): bool
    {
        return $this->hasProtectedAccess;
    }

    public function canBeBoughtOnEshop(): bool
    {
        return $this->canBeBoughtOnEshop;
    }

    public function hasCharacterSheet(): bool
    {
        return $this->hasCharacterSheet;
    }

    public function hasLinksToJournals(): bool
    {
        return $this->hasLinksToJournals;
    }

    public function hasLinkToSingleJournal(): bool
    {
        return $this->hasLinkToSingleJournal;
    }

    public function hasDebugContacts(): bool
    {
        return $this->hasDebugContacts;
    }

    public function getExpectedLicence(): string
    {
        if ($this->expectedLicence !== self::LICENCE_BY_ACCESS) {
            return $this->expectedLicence;
        }

        return $this->hasProtectedAccess()
            ? self::LICENCE_PROPRIETARY
            : self::LICENCE_MIT;
    }

    /** @return array|string[] */
    public function getTooShortFailureNames(): array
    {
        return $this->tooShortFailureNames;
    }

    /** @return array|string[] */
    public function getTooShortSuccessNames(): array
    {
        return $this->tooShortSuccessNames;
    }

    /** @return array|string[] */
    public function getTooShortResultNames(): array
    {
        return $this->tooShortResultNames;
    }
}