<?php declare(strict_types=1);
namespace DrdPlus\RulesSkeleton;

use Composer\Composer;
use Composer\DependencyResolver\Operation\InstallOperation;
use Composer\DependencyResolver\Operation\UpdateOperation;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Granam\Strict\Object\StrictObject;
use Granam\WebContentBuilder\AssetsVersion;

class SkeletonInjectorComposerPlugin extends StrictObject implements PluginInterface, EventSubscriberInterface
{
    public const RULES_SKELETON_PACKAGE_NAME = 'drdplus/rules-skeleton';

    /** @var Composer */
    private $composer;
    /** @var IOInterface */
    private $io;
    /** @var bool */
    private $alreadyInjected = false;
    /** @var string */
    private $skeletonPackageName;

    public static function getSubscribedEvents(): array
    {
        return [
            PackageEvents::POST_PACKAGE_INSTALL => 'plugInSkeleton',
            PackageEvents::POST_PACKAGE_UPDATE => 'plugInSkeleton',
        ];
    }

    public function __construct()
    {
        $this->skeletonPackageName = static::RULES_SKELETON_PACKAGE_NAME;
    }

    public function activate(Composer $composer, IOInterface $io): void
    {
        $this->composer = $composer;
        $this->io = $io;
    }

    public function plugInSkeleton(PackageEvent $event)
    {
        if ($this->alreadyInjected || !$this->isThisPackageChanged($event)) {
            return;
        }
        $documentRoot = $GLOBALS['documentRoot'] ?? getcwd();
        $this->io->write("Injecting {$this->skeletonPackageName} using document root $documentRoot");
        $this->publishSkeletonImages($documentRoot);
        $this->publishSkeletonCss($documentRoot);
        $this->publishSkeletonJs($documentRoot);
        $this->copyProjectConfig($documentRoot);
        $this->copyFavicon($documentRoot);
        $this->addVersionsToAssets($documentRoot);
        $this->flushCache($documentRoot);
        $this->copyGoogleVerification($documentRoot);
        $this->copyPhpUnitConfig($documentRoot);
        $this->copyGitignoreToCache($documentRoot);
        $this->copyRoutes($documentRoot);
        $this->alreadyInjected = true;
        $this->io->write("Injection of {$this->skeletonPackageName} finished");
    }

    private function isThisPackageChanged(PackageEvent $event): bool
    {
        /** @var InstallOperation|UpdateOperation $operation */
        $operation = $event->getOperation();
        if ($operation instanceof InstallOperation) {
            $changedPackageName = $operation->getPackage()->getName();
        } elseif ($operation instanceof UpdateOperation) {
            $changedPackageName = $operation->getInitialPackage()->getName();
        } else {
            return false;
        }

        return $this->isChangedPackageThisOne($changedPackageName);
    }

    private function isChangedPackageThisOne(string $changedPackageName): bool
    {
        return $changedPackageName === $this->skeletonPackageName;
    }

    private function publishSkeletonImages(string $documentRoot): void
    {
        $this->passThrough(
            [
                'rm -fr ./images/generic/skeleton/',
                'mkdir -p ./images/generic/skeleton/',
                "cp -r ./vendor/{$this->skeletonPackageName}/images/generic/skeleton/* ./images/generic/skeleton/",
            ],
            $documentRoot
        );
    }

    private function passThrough(array $commands, string $workingDir = null): void
    {
        if ($workingDir !== null) {
            $escapedWorkingDir = \escapeshellarg($workingDir);
            \array_unshift($commands, 'cd ' . $escapedWorkingDir);
        }
        foreach ($commands as &$command) {
            $command .= ' 2>&1';
        }
        unset($command);
        $chain = \implode(' && ', $commands);
        \exec($chain, $output, $returnCode);
        if ($returnCode !== 0) {
            $this->io->writeError(
                "Failed injecting skeleton by command $chain\nGot return code $returnCode and output " . \implode("\n", $output)
            );

            return;
        }
        $this->io->write($chain);
        if ($output) {
            $this->io->write(' ' . \implode("\n", $output));
        }
    }

    private function copyGoogleVerification(string $documentRoot): void
    {
        $this->passThrough(["cp ./vendor/{$this->skeletonPackageName}/google8d8724e0c2818dfc.html ."], $documentRoot);
    }

    private function copyPhpUnitConfig(string $documentRoot): void
    {
        $this->passThrough(["cp --no-clobber ./vendor/$this->skeletonPackageName/phpunit.xml.dist ."], $documentRoot);
    }

    private function addVersionsToAssets(string $documentRoot)
    {
        $assetsVersion = new AssetsVersion(true, false);
        $changedFiles = $assetsVersion->addVersionsToAssetLinks($documentRoot, ['css'], [], [], false);
        if ($changedFiles) {
            $this->io->write('Those assets got versions to asset links: ' . \implode(', ', $changedFiles));
        }
    }

    private function flushCache(string $documentRoot): void
    {
        $this->passThrough(['find ./cache -mindepth 2 -type f -exec rm {} +'], $documentRoot);
    }

    private function publishSkeletonCss(string $documentRoot): void
    {
        $this->passThrough(
            [
                'rm -fr ./css/generic/skeleton/',
                'mkdir -p ./css/generic/skeleton/',
                "cp -r ./vendor/{$this->skeletonPackageName}/css/generic/skeleton/* ./css/generic/skeleton/",
            ],
            $documentRoot
        );
    }

    private function publishSkeletonJs(string $documentRoot): void
    {
        $this->passThrough(
            [
                'rm -fr ./js/generic/skeleton/',
                'mkdir -p ./js/generic/skeleton/',
                "cp -r ./vendor/{$this->skeletonPackageName}/js/generic/skeleton/* ./js/generic/skeleton/",
            ],
            $documentRoot
        );
    }

    private function copyProjectConfig(string $documentRoot): void
    {
        if (!\file_exists('config.distribution.yml')) {
            $this->passThrough(["cp --no-clobber ./vendor/{$this->skeletonPackageName}/config.distribution.yml ."], $documentRoot);

            return;
        }
        $rulesSkeletonConfigContent = \file_get_contents("vendor/{$this->skeletonPackageName}/config.distribution.yml");
        if (\file_get_contents('config.distribution.yml') !== $rulesSkeletonConfigContent) {
            return;
        }
        $this->passThrough(["cp ./vendor/$this->skeletonPackageName/config.distribution.yml ."], $documentRoot);
    }

    private function copyGitignoreToCache(string $documentRoot): void
    {
        $this->passThrough(
            [
                'mkdir -p cache',
                'chmod 0775 cache',
                'chgrp www-data cache',
                "cp ./vendor/{$this->skeletonPackageName}/cache/.gitignore ./cache/.gitignore",
            ],
            $documentRoot
        );
    }

    private function copyFavicon(string $documentRoot): void
    {
        $this->passThrough(["cp ./vendor/{$this->skeletonPackageName}/favicon.ico ."], $documentRoot);
    }

    private function copyRoutes(string $documentRoot): void
    {
        $this->passThrough(["cp --no-clobber ./vendor/{$this->skeletonPackageName}/routes.yml ."], $documentRoot);
    }
}