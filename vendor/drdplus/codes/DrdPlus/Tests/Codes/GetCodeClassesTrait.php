<?php
namespace DrdPlus\Tests\Codes;

use DrdPlus\Codes\Code;
use DrdPlus\Codes\Partials\AbstractCode;

/**
 * @method static assertRegExp($regexp, $value, $message = '')
 */
trait GetCodeClassesTrait
{

    private static $codeClasses;

    /**
     * @return array|AbstractCode[]
     * @throws \ReflectionException
     */
    protected function getCodeClasses(): array
    {
        if (self::$codeClasses === null) {
            $codeReflection = new \ReflectionClass(Code::class);
            $rootDir = \dirname($codeReflection->getFileName());
            $rootNamespace = $codeReflection->getNamespaceName();

            self::$codeClasses = $this->scanForCodeClasses($rootDir, $rootNamespace);
        }

        return self::$codeClasses;
    }

    /**
     * @param string $rootDir
     * @param string $rootNamespace
     * @return array
     * @throws \ReflectionException
     */
    protected function scanForCodeClasses(string $rootDir, string $rootNamespace): array
    {
        $codeClasses = [];
        foreach (\scandir($rootDir, SCANDIR_SORT_NONE) as $folder) {
            $folderFullPath = $rootDir . DIRECTORY_SEPARATOR . $folder;
            if ($folder !== '.' && $folder !== '..') {
                if (\is_dir($folderFullPath)) {
                    foreach ($this->scanForCodeClasses($folderFullPath, $rootNamespace . '\\' . $folder) as $foundCode) {
                        $codeClasses[] = $foundCode;
                    }
                } else if (\is_file($folderFullPath) && \preg_match('~(?<classBasename>\w+(?:Code)?)\.php$~', $folder, $matches)) {
                    $reflectionClass = new \ReflectionClass($rootNamespace . '\\' . $matches['classBasename']);
                    if (!$reflectionClass->isAbstract() && $reflectionClass->implementsInterface(Code::class)) {
                        self::assertRegExp(
                            '~Code$~',
                            $reflectionClass->getName(),
                            'Every single code should ends by "Code"'
                        );
                        $codeClasses[] = $reflectionClass->getName();
                    }
                }
            }
        }

        return $codeClasses;
    }
}