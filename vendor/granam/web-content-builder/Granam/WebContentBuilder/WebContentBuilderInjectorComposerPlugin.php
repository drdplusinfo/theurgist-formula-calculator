<?php
namespace Granam\WebContentBuilder;

use Composer\Composer;
use Composer\DependencyResolver\Operation\InstallOperation;
use Composer\DependencyResolver\Operation\UpdateOperation;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Granam\Strict\Object\StrictObject;

class WebContentBuilderInjectorComposerPlugin extends StrictObject implements PluginInterface, EventSubscriberInterface
{
    public const LIBRARY_PACKAGE_NAME = 'granam/web-content-builder';

    /** @var Composer */
    private $composer;
    /** @var IOInterface */
    private $io;
    /** @var bool */
    private $alreadyInjected = false;
    /** @var string */
    private $libraryPackageName;

    public static function getSubscribedEvents(): array
    {
        return [
            PackageEvents::POST_PACKAGE_INSTALL => 'plugInLibrary',
            PackageEvents::POST_PACKAGE_UPDATE => 'plugInLibrary',
        ];
    }

    public function __construct()
    {
        $this->libraryPackageName = static::LIBRARY_PACKAGE_NAME;
    }

    public function activate(Composer $composer, IOInterface $io): void
    {
        $this->composer = $composer;
        $this->io = $io;
    }

    public function plugInLibrary(PackageEvent $event)
    {
        if ($this->alreadyInjected || !$this->isThisPackageChanged($event)) {
            return;
        }
        $dirs = Dirs::createFromGlobals();
        $this->io->write("Injecting {$this->libraryPackageName} using project root {$dirs->getProjectRoot()}");
        $this->addVersionsToAssets($dirs);
        $this->alreadyInjected = true;
        $this->io->write("Injection of {$this->libraryPackageName} finished");
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
        return $changedPackageName === $this->libraryPackageName;
    }

    private function addVersionsToAssets(Dirs $dirs)
    {
        if (!\is_dir($dirs->getCssRoot())) {
            return;
        }
        $assetsVersion = new AssetsVersion(true, false);
        $changedFiles = $assetsVersion->addVersionsToAssetLinks($dirs->getProjectRoot(), [$dirs->getCssRoot()], [], [], false);
        if ($changedFiles) {
            $this->io->write('Those assets got versions to asset links: ' . \implode(', ', $changedFiles));
        }
    }
}