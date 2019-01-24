<?php
namespace DrdPlus\CalculatorSkeleton;

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

class CalculatorInjectorComposerPlugin extends StrictObject implements PluginInterface, EventSubscriberInterface
{
    public const CALCULATOR_SKELETON_PACKAGE_NAME = 'drdplus/calculator-skeleton';

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
        $this->skeletonPackageName = static::CALCULATOR_SKELETON_PACKAGE_NAME;
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
        $this->publishSkeletonCss($documentRoot);
        $this->publishSkeletonJs($documentRoot);
        $this->addVersionsToAssets($documentRoot);
        $this->flushCache($documentRoot);
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

    private function publishSkeletonCss(string $documentRoot): void
    {
        $this->passThrough(
            [
                'rm -fr ./css/generic/calculator/',
                'mkdir -p ./css/generic/calculator/',
                "cp -r ./vendor/{$this->skeletonPackageName}/css/generic/calculator/* ./css/generic/calculator/",
            ],
            $documentRoot
        );
    }

    private function publishSkeletonJs(string $documentRoot): void
    {
        $this->passThrough(
            [
                'rm -fr ./js/generic/calculator/',
                'mkdir -p ./js/generic/calculator/',
                "cp -r ./vendor/{$this->skeletonPackageName}/js/generic/calculator/* ./js/generic/calculator/",
            ],
            $documentRoot
        );
    }

    private function addVersionsToAssets(string $documentRoot)
    {
        $assetsVersion = new AssetsVersion(true, false);
        $changedFiles = $assetsVersion->addVersionsToAssetLinks($documentRoot, ['css/generic/calculator'], [], [], false);
        if ($changedFiles) {
            $this->io->write('Those assets got versions to asset links: ' . \implode(', ', $changedFiles));
        }
    }

    private function flushCache(string $documentRoot): void
    {
        $this->passThrough(['find ./cache -mindepth 2 -type f -exec rm {} +'], $documentRoot);
    }

}