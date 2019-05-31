<?php
declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton\Partials;

use DeviceDetector\Parser\Bot;
use DrdPlus\RulesSkeleton\Configuration;
use DrdPlus\RulesSkeleton\ContentIrrelevantParametersFilter;
use DrdPlus\RulesSkeleton\CookiesService;
use DrdPlus\RulesSkeleton\CurrentWebVersion;
use DrdPlus\RulesSkeleton\Dirs;
use DrdPlus\RulesSkeleton\Environment;
use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\RulesSkeleton\Request;
use DrdPlus\RulesSkeleton\RulesApplication;
use DrdPlus\RulesSkeleton\ServicesContainer;
use DrdPlus\RulesSkeleton\UsagePolicy;
use DrdPlus\RulesSkeleton\Web\RulesMainBody;
use DrdPlus\Tests\RulesSkeleton\TestsConfiguration;
use DrdPlus\WebVersions\WebVersions;
use Granam\Git\Git;
use Granam\String\StringTools;
use Granam\Tests\Tools\TestWithMockery;
use Granam\WebContentBuilder\HtmlDocument;
use Granam\WebContentBuilder\Web\HeadInterface;
use Gt\Dom\Element;
use Mockery\MockInterface;

abstract class AbstractContentTest extends TestWithMockery
{
    use ClassesTrait;

    /** @var Dirs */
    private $dirs;
    /** @var Environment */
    private $environment;
    /** @var TestsConfiguration */
    private $testsConfiguration;
    protected $needPassIn = true;
    protected $needPassOut = false;
    /** @var Configuration */
    private $configuration;
    private $frontendSkeletonChecked;

    protected function setUp(): void
    {
        if (!\defined('DRD_PLUS_INDEX_FILE_NAME_TO_TEST')) {
            self::markTestSkipped("Missing constant 'DRD_PLUS_INDEX_FILE_NAME_TO_TEST'");
        }
        if ($this->getTestsConfiguration()->hasProtectedAccess()) {
            $this->passIn();
        }
    }

    protected function getTestsConfiguration(): TestsConfiguration
    {
        static $testsConfiguration;
        if ($testsConfiguration === null) {
            $testsConfiguration = TestsConfiguration::createFromYaml(\DRD_PLUS_TESTS_ROOT . '/tests_configuration.yml');
        }

        return $testsConfiguration;
    }

    protected function passIn(): bool
    {
        $_COOKIE[$this->getNameForLocalOwnershipConfirmation()] = true; // this cookie simulates confirmation of ownership
        $usagePolicy = new UsagePolicy($this->getVariablePartOfNameForPass(), new Request($this->getBot()), new CookiesService());
        self::assertTrue(
            $usagePolicy->hasVisitorConfirmedOwnership(),
            "Ownership has not been confirmed by cookie '{$this->getNameForLocalOwnershipConfirmation()}'"
        );
        $this->needPassOut = false;
        $this->needPassIn = true;

        return true;
    }

    protected function getBot(): Bot
    {
        static $bot;
        if ($bot === null) {
            $bot = new Bot();
        }
        return $bot;
    }

    protected function passOut(): bool
    {
        unset($_COOKIE[$this->getNameForLocalOwnershipConfirmation()]);
        $usagePolicy = new UsagePolicy($this->getVariablePartOfNameForPass(), new Request($this->getBot()), new CookiesService());
        self::assertFalse(
            $usagePolicy->hasVisitorConfirmedOwnership(),
            "Ownership is still confirmed by cookie '{$this->getNameForLocalOwnershipConfirmation()}'"
        );
        $this->needPassOut = true;
        $this->needPassIn = false;

        return true;
    }

    /**
     * @param array $get = []
     * @param array $post = []
     * @param array $cookies = []
     * @return string
     */
    protected function getContent(array $get = [], array $post = [], array $cookies = []): string
    {
        static $contents = [];
        $key = $this->createKey($get, $post, $cookies);
        if (($contents[$key] ?? null) === null) {
            $originalGet = $_GET;
            $originalPost = $_POST;
            $originalCookies = $_COOKIE;
            if ($get) {
                $_GET = \array_merge($_GET, $get);
            }
            if ($post) {
                $_POST = \array_merge($_POST, $post);
            }
            if ($cookies) {
                $_COOKIE = \array_merge($_COOKIE, $cookies);
            }
            if ($this->needPassIn()) {
                $this->passIn();
            } elseif ($this->needPassOut()) {
                $this->passOut();
            }
            \ob_start();
            /** @noinspection PhpIncludeInspection */
            include DRD_PLUS_INDEX_FILE_NAME_TO_TEST;
            $contents[$key] = \ob_get_clean();
            $_POST = $originalPost;
            $_GET = $originalGet;
            $_COOKIE = $originalCookies;
            self::assertNotEmpty(
                $contents[$key],
                'Nothing has been fetched with GET ' . \var_export($get, true) . ', POST ' . \var_export($post, true)
                . ' and COOKIE ' . \var_export($cookies, true)
                . ' from ' . DRD_PLUS_INDEX_FILE_NAME_TO_TEST
            );
        }

        return $contents[$key];
    }

    protected function createKey(array $get, array $post, array $cookies): string
    {
        return \json_encode($get) . '-' . \json_encode($post) . '-' . \json_encode($cookies) . '-' . (int)$this->needPassIn() . (int)$this->needPassOut();
    }

    protected function needPassIn(): bool
    {
        return $this->needPassIn;
    }

    protected function needPassOut(): bool
    {
        return $this->needPassOut;
    }

    /**
     * @param array $get
     * @param array $post
     * @param array $cookies
     * @return HtmlDocument
     */
    protected function getHtmlDocument(array $get = [], array $post = [], array $cookies = []): HtmlDocument
    {
        static $htmlDocuments = [];
        $key = $this->createKey($get, $post, $cookies);
        if (($htmlDocuments[$key] ?? null) === null) {
            $htmlDocuments[$key] = new HtmlDocument($this->getContent($get, $post, $cookies));
        }

        return $htmlDocuments[$key];
    }

    protected function fetchHtmlDocumentFromLocalUrl(): HtmlDocument
    {
        $content = $this->fetchContentFromLink($this->getTestsConfiguration()->getLocalUrl(), true)['content'];
        self::assertNotEmpty($content);

        return new HtmlDocument($content);
    }

    protected function getCurrentPageTitle(HTMLDocument $document = null): string
    {
        $head = ($document ?? $this->getHtmlDocument())->head;
        if (!$head) {
            return '';
        }
        $titles = $head->getElementsByTagName('title');
        if ($titles->count() === 0) {
            return '';
        }
        $titles->rewind();

        return $titles->current()->nodeValue;
    }

    protected function getHtmlHelper(): HtmlHelper
    {
        static $htmlHelper;
        if ($htmlHelper === null) {
            $htmlHelper = $this->createHtmlHelper();
        }

        return $htmlHelper;
    }

    /**
     * @param Dirs $dirs
     * @param bool $inDevMode
     * @param bool $inForcedProductionMode
     * @param bool $shouldHideCovered
     * @return HtmlHelper|\Mockery\MockInterface
     */
    protected function createHtmlHelper(
        Dirs $dirs = null,
        bool $inForcedProductionMode = false,
        bool $inDevMode = false,
        bool $shouldHideCovered = false
    ): HtmlHelper
    {
        return new HtmlHelper($dirs ?? $this->getDirs(), $this->getEnvironment(), $inDevMode, $inForcedProductionMode, $shouldHideCovered);
    }

    protected function fetchNonCachedContent(RulesApplication $rulesApplication = null, bool $backupGlobals = true): string
    {
        $originalGet = $_GET;
        $originalPost = $_POST;
        $originalCookies = $_COOKIE;
        /** @noinspection PhpUnusedLocalVariableInspection */
        $rulesApplication = $rulesApplication ?? null;
        $_GET[Request::CACHE] = Request::DISABLE;
        \ob_start();
        /** @noinspection PhpIncludeInspection */
        include $this->getProjectRoot() . '/index.php';
        $content = \ob_get_clean();
        if ($backupGlobals) {
            $_GET = $originalGet;
            $_POST = $originalPost;
            $_COOKIE = $originalCookies;
        }

        return $content;
    }

    protected const WITH_BODY = true;
    protected const WITHOUT_BODY = true;

    protected function fetchContentFromLink(string $link, bool $withBody, array $post = [], array $cookies = [], array $headers = []): array
    {
        static $cachedContent = [];
        $key = $link . ($withBody ? '1' : '0') . $this->createKey($headers, $post, $cookies);
        if (($cachedContent[$key] ?? null) === null) {
            $curl = \curl_init($link);
            \curl_setopt($curl, \CURLOPT_RETURNTRANSFER, 1);
            \curl_setopt($curl, \CURLOPT_CONNECTTIMEOUT, 7);
            if (!$withBody) {
                // to get headers only
                \curl_setopt($curl, \CURLOPT_HEADER, 1);
                \curl_setopt($curl, \CURLOPT_NOBODY, 1);
            }
            \curl_setopt($curl, \CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:58.0) Gecko/20100101 Firefox/58.0'); // to get headers only
            if ($post) {
                \curl_setopt($curl, \CURLOPT_POSTFIELDS, $post);
            }
            if ($cookies) {
                $cookieData = [];
                foreach ($cookies as $name => $value) {
                    $cookieData[] = "$name=$value";
                }
                \curl_setopt($curl, \CURLOPT_COOKIE, \implode('; ', $cookieData));
            }
            foreach ($headers as $headerName => $headerValue) {
                \curl_setopt($curl, \CURLOPT_HEADER, "$headerName=$headerValue");
            }
            $content = \curl_exec($curl);
            $responseHttpCode = \curl_getinfo($curl, \CURLINFO_HTTP_CODE);
            $redirectUrl = \curl_getinfo($curl, \CURLINFO_REDIRECT_URL);
            $curlError = \curl_error($curl);
            \curl_close($curl);
            $cachedContent[$key] = [
                'responseHttpCode' => $responseHttpCode,
                'redirectUrl' => $redirectUrl,
                'content' => $content,
                'error' => $curlError,
            ];
        }

        return $cachedContent[$key];
    }

    protected function runCommand(string $command): array
    {
        \exec("$command 2>&1", $output, $returnCode);
        self::assertSame(0, $returnCode, "Failed command '$command', got output " . \var_export($output, true));

        return $output;
    }

    protected function executeCommand(string $command): string
    {
        $output = $this->runCommand($command);

        return \end($output) ?: '';
    }

    /**
     * @param HtmlDocument $document
     * @return array|Element[]
     */
    protected function getMetaRefreshes(HtmlDocument $document): array
    {
        $metaElements = $document->head->getElementsByTagName('meta');
        $metaRefreshes = [];
        foreach ($metaElements as $metaElement) {
            if ($metaElement->getAttribute('http-equiv') === 'Refresh') {
                $metaRefreshes[] = $metaElement;
            }
        }

        return $metaRefreshes;
    }

    protected function getGitFolderIgnoring(string $dirToCheck): array
    {
        $documentRootEscaped = \escapeshellarg($this->getProjectRoot());
        $dirToCheckEscaped = \escapeshellarg($dirToCheck);
        $command = "git -C $documentRootEscaped check-ignore $dirToCheckEscaped 2>&1";
        \exec($command, $output, $result);
        if ($result > 1) { // both 0 and 1 are valid success return codes
            throw new \RuntimeException(
                "Can not find out if is vendor dir versioned or not by command '{$command}'"
                . ", got return code '{$result}' and output\n"
                . \implode("\n", $output)
            );
        }

        return ['output' => $output, 'result' => $result];
    }

    protected function getConfiguration(Dirs $dirs = null): Configuration
    {
        if ($this->configuration === null) {
            $configurationClass = $this->getConfigurationClass();
            $this->configuration = $configurationClass::createFromYml($dirs ?? $this->getDirs());
        }

        return $this->configuration;
    }

    /**
     * @param array $values
     * @return Request|MockInterface
     */
    protected function createRequest(array $values = []): Request
    {
        $request = $this->mockery($this->getRequestClass());
        foreach ($values as $name => $value) {
            $request->allows('getValue')
                ->with($name)
                ->andReturn($value);
        }
        $request->makePartial();

        return $request;
    }

    protected function getContentIrrelevantParametersFilter(): ContentIrrelevantParametersFilter
    {
        static $contentIrrelevantParametersFilter;
        if ($contentIrrelevantParametersFilter === null) {
            $contentIrrelevantParametersFilter = $this->createServicesContainer()->getContentIrrelevantParametersFilter();
        }
        return $contentIrrelevantParametersFilter;
    }

    protected function createGit(): Git
    {
        return new Git();
    }

    /**
     * @param array $customSettings
     * @return Configuration|MockInterface
     */
    protected function createCustomConfiguration(array $customSettings): Configuration
    {
        $originalConfiguration = $this->getConfiguration();
        $configurationClass = \get_class($originalConfiguration);
        $customConfiguration = new $configurationClass(
            $originalConfiguration->getDirs(),
            \array_replace_recursive($originalConfiguration->getSettings(), $customSettings)
        );
        /** Configuration */
        return $customConfiguration;
    }

    protected function createRulesApplication(
        Configuration $configuration = null,
        HtmlHelper $htmlHelper = null
    ): RulesApplication
    {
        $rulesApplicationClass = $this->getRulesApplicationClass();

        return new $rulesApplicationClass($this->createServicesContainer($configuration, $htmlHelper));
    }

    protected function getServicesContainer(): ServicesContainer
    {
        static $servicesContainer;
        if ($servicesContainer === null) {
            $servicesContainer = $this->createServicesContainer();
        }
        return $servicesContainer;
    }

    protected function createServicesContainer(Configuration $configuration = null, HtmlHelper $htmlHelper = null): ServicesContainer
    {
        return new ServicesContainer(
            $configuration ?? $this->getConfiguration(),
            $htmlHelper ?? $this->createHtmlHelper($this->getDirs())
        );
    }

    /**
     * @return Dirs|\Granam\WebContentBuilder\Dirs
     */
    protected function getDirs(): \Granam\WebContentBuilder\Dirs
    {
        if ($this->dirs === null) {
            $this->dirs = $this->createDirs($this->getProjectRoot());
        }
        return $this->dirs;
    }

    protected function createDirs(string $projectRoot): Dirs
    {
        $dirsClass = $this->getDirsClass();

        return new $dirsClass($projectRoot);
    }

    protected function getDirsClass(): string
    {
        return Dirs::class;
    }

    protected function getEnvironment(): Environment
    {
        if ($this->environment === null) {
            $this->environment = new Environment();
        }
        return $this->environment;
    }

    /**
     * @return array|\Closure[]
     */
    protected function getLicenceSwitchers(): array
    {
        return [[$this, 'passIn'], [$this, 'passOut']];
    }

    protected function isRulesSkeletonChecked(): bool
    {
        return $this->isSkeletonChecked($this->getRulesSkeletonProjectRoot());
    }

    private function getRulesSkeletonProjectRoot(): string
    {
        return __DIR__ . '/../../../..';
    }

    protected function isSkeletonChecked(string $skeletonDocumentRoot = null): bool
    {
        static $skeletonChecked = [];
        $skeletonDocumentRoot = $skeletonDocumentRoot ?? $this->getRulesSkeletonProjectRoot();
        if (($skeletonChecked[$skeletonDocumentRoot] ?? null) === null) {
            $projectRootRealPath = \realpath($this->getProjectRoot());
            self::assertNotEmpty($projectRootRealPath, 'Can not find out real path of project root ' . \var_export($this->getProjectRoot(), true));
            $skeletonRootRealPath = \realpath($skeletonDocumentRoot);
            self::assertNotEmpty($skeletonRootRealPath, 'Can not find out real path of skeleton root ' . \var_export($skeletonRootRealPath, true));
            $skeletonChecked[$skeletonDocumentRoot] = $projectRootRealPath === $skeletonRootRealPath;
        }

        return $skeletonChecked[$skeletonDocumentRoot];
    }

    protected function getPassDocument(bool $notCached = false): HtmlDocument
    {
        if ($notCached) {
            return new HtmlDocument($this->getPassContent($notCached));
        }
        static $passDocument;
        if ($passDocument === null) {
            $this->removeOwnerShipConfirmation();
            $passDocument = new HtmlDocument($this->getPassContent($notCached));
        }

        return $passDocument;
    }

    /**
     * @param bool $notCached
     * @return string
     */
    protected function getPassContent(bool $notCached = false): string
    {
        if ($notCached) {
            $this->removeOwnerShipConfirmation();

            return $this->fetchNonCachedContent();
        }
        static $passContent;
        if ($passContent === null) {
            $this->removeOwnerShipConfirmation();
            $passContent = $this->fetchNonCachedContent();
        }

        return $passContent;
    }

    private function getNameForLocalOwnershipConfirmation(): string
    {
        static $cookieName;
        if ($cookieName === null) {
            $cookieName = $this->getNameForOwnershipConfirmation();
        }

        return $cookieName;
    }

    protected function getNameForOwnershipConfirmation(): string
    {
        static $nameOfOwnershipConfirmation;
        if ($nameOfOwnershipConfirmation === null) {
            $usagePolicy = new UsagePolicy($this->getVariablePartOfNameForPass(), new Request($this->getBot()), new CookiesService());
            try {
                $usagePolicyReflection = new \ReflectionClass(UsagePolicy::class);
            } catch (\ReflectionException $reflectionException) {
                self::fail($reflectionException->getMessage());
                exit;
            }
            /** @noinspection PhpUnhandledExceptionInspection */
            $getName = $usagePolicyReflection->getMethod('getOwnershipName');
            $getName->setAccessible(true);
            $nameOfOwnershipConfirmation = $getName->invoke($usagePolicy);
        }

        return $nameOfOwnershipConfirmation;
    }

    protected function getVariablePartOfNameForPass(): string
    {
        return StringTools::toVariableName($this->getTestsConfiguration()->getExpectedWebName());
    }

    private function removeOwnerShipConfirmation(): void
    {
        unset($_COOKIE[$this->getNameForLocalOwnershipConfirmation()]);
    }

    /**
     * @param string $show = ''
     * @param string $hide = ''
     * @return string
     */
    protected function getRulesContentForDev(string $show = '', string $hide = ''): string
    {
        static $rulesContentForDev = [];
        if (($rulesContentForDev[$show][$hide] ?? null) === null) {
            $get['mode'] = 'dev';
            if ($show !== '') {
                $get['show'] = $show;
            }
            if ($hide !== '') {
                $get['hide'] = $hide;
            }
            $content = $this->getContent($get);
            $rulesContentForDev[$show][$hide] = $content;
            self::assertNotSame($this->getPassContent(), $rulesContentForDev[$show]);
        }

        return $rulesContentForDev[$show][$hide];
    }

    protected function getRulesForDevHtmlDocument(string $show = '', string $hide = ''): HTMLDocument
    {
        static $rulesForDevHtmlDocument = [];
        if (($rulesForDevHtmlDocument[$show][$hide] ?? null) === null) {
            $rulesForDevHtmlDocument[$show][$hide] = new HTMLDocument($this->getRulesContentForDev($show, $hide));
        }

        return $rulesForDevHtmlDocument[$show][$hide];
    }

    /**
     * @return string
     */
    protected function getRulesContentForDevWithHiddenCovered(): string
    {
        return $this->getRulesContentForDev('', 'covered');
    }

    /**
     * @return string|Configuration
     */
    protected function getConfigurationClass(): string
    {
        return Configuration::class;
    }

    protected function getRulesApplicationClass(): string
    {
        return RulesApplication::class;
    }

    protected function unifyPath(string $path): string
    {
        $path = \str_replace('\\', '/', $path);
        $path = \preg_replace('~/[.](?:/|$)~', '/', $path);

        return $this->squashTwoDots($path);
    }

    private function squashTwoDots(string $path): string
    {
        $originalPath = $path;
        $path = \preg_replace('~/[^/.]+/[.]{2}~', '', $path);
        if ($originalPath === $path) {
            return $originalPath; // nothing has been squashed
        }

        return $this->squashTwoDots($path);
    }

    protected function getSkeletonProjectRoot(): string
    {
        if ($this->isSkeletonChecked()) {
            return $this->getProjectRoot();
        }

        return $this->getDirs()->getVendorRoot() . '/drdplus/rules-skeleton';
    }

    protected function getVendorRoot(): string
    {
        return $this->getDirs()->getVendorRoot();
    }

    protected function createWebVersions(Git $git = null, string $repositoryDir = null): WebVersions
    {
        return new WebVersions($git ?? $this->createGit(), $repositoryDir ?? $this->getDirs()->getProjectRoot());
    }

    protected function createCurrentWebVersion(
        Dirs $dirs = null,
        Git $git = null,
        WebVersions $webVersions = null
    ): CurrentWebVersion
    {
        /** @var CurrentWebVersion $currentWebVersionClass */
        $currentWebVersionClass = $this->getCurrentWebVersionClass();

        return new $currentWebVersionClass(
            $dirs ?? $this->getDirs(),
            $git ?? $this->createGit(),
            $webVersions ?? $this->createWebVersions($git)
        );
    }

    protected function getCurrentWebVersion(): CurrentWebVersion
    {
        static $currentWebVersion;
        if ($currentWebVersion === null) {
            $currentWebVersion = $this->createCurrentWebVersion();
        }

        return $currentWebVersion;
    }

    protected function getProjectRoot(): string
    {
        static $projectRoot;
        if ($projectRoot === null) {
            self::assertDirectoryExists(\DRD_PLUS_PROJECT_ROOT, 'Project root has not been found');
            $projectRoot = \DRD_PLUS_PROJECT_ROOT;
        }

        return $projectRoot;
    }

    protected function createEmptyHead(): HeadInterface
    {
        return new class implements HeadInterface
        {
            public function __toString()
            {
                return $this->getValue();
            }

            public function getValue(): string
            {
                return '';
            }

        };
    }

    /**
     * @param string $content
     * @return RulesMainBody|MockInterface
     */
    protected function createMainBody(string $content): RulesMainBody
    {
        $rulesMainBody = $this->mockery(RulesMainBody::class);
        $rulesMainBody->shouldReceive('getValue')
            ->andReturn($content);
        $rulesMainBody->makePartial();

        return $rulesMainBody;
    }

    protected function getComposerConfig(): array
    {
        static $composerConfig;
        if ($composerConfig === null) {
            $composerFilePath = $this->getProjectRoot() . '/composer.json';
            self::assertFileExists($composerFilePath, 'composer.json has not been found in document root');
            $content = \file_get_contents($composerFilePath);
            self::assertNotEmpty($content, "Nothing has been fetched from $composerFilePath, is readable?");
            $composerConfig = \json_decode($content, true /*as array */);
            self::assertIsArray($composerConfig, 'Can not decode composer.json content fetched from ' . $composerFilePath);
        }

        return $composerConfig;
    }

    /**
     * @param HtmlDocument $htmlDocument
     * @return array|string[]
     */
    protected function parseTableIds(HtmlDocument $htmlDocument): array
    {
        $tableIds = [];
        foreach ($htmlDocument->getElementsByTagName('table') as $table) {
            if (\preg_match('~\sid\s*=\s*"(?<id>[^"]+)~', $table->prop_get_outerHTML(), $idMatch)) {
                $tableIds[] = \html_entity_decode($idMatch['id']);
            }
        }

        return $tableIds;
    }

    protected function parseAllIds(HtmlDocument $htmlDocument): array
    {
        if (!\preg_match_all('~\sid\s*=\s*"(?<id>[^"]+)~', $htmlDocument->saveHTML(), $idMatches)) {
            return [];
        }
        $ids = [];
        foreach ($idMatches['id'] as $id) {
            $ids[] = \html_entity_decode($id);
        }
        return $ids;
    }

    /**
     * @test
     */
    public function Globals_are_cleaned(): void
    {
        self::assertCount(0, $_GET, 'Global $_GET is not empty, have you forgot to set @backupGlobals enabled ?, ' . \var_export($_GET, true));
        self::assertCount(0, $_POST, 'Global $_POST is not empty, have you forgot to set @backupGlobals enabled ? ' . \var_export($_POST, true));
        if (!$this->getTestsConfiguration()->hasProtectedAccess()) {
            self::assertCount(0, $_COOKIE, 'Global $_COOKIE is not empty, have you forgot to set @backupGlobals enabled ? ' . \var_export($_COOKIE, true));
        } else {
            $allowedCookieKeys = [
                UsagePolicy::OWNERSHIP_COOKIE_NAME,
                UsagePolicy::TRIAL_COOKIE_NAME,
                UsagePolicy::TRIAL_EXPIRED_AT_COOKIE_NAME,
            ];
            if (isset($_COOKIE[UsagePolicy::OWNERSHIP_COOKIE_NAME])) {
                $allowedCookieKeys[] = $_COOKIE[UsagePolicy::OWNERSHIP_COOKIE_NAME];
            }
            $cookieTrash = \array_diff_key($_COOKIE, \array_fill_keys($allowedCookieKeys, 'foo'));
            self::assertCount(
                0,
                $cookieTrash,
                'Global $_COOKIE after filtering pass-related values is not empty, have you forgot to set @backupGlobals enabled ? ' . \var_export($cookieTrash, true)
            );
        }
    }
}