<?php declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Theurgist\Demons\DemonParameters;

use DrdPlus\Codes\Partials\AbstractCode;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonCapacity;
use Granam\Tests\Tools\TestWithMockery;

class AllDemonParametersTest extends TestWithMockery
{
    /**
     * @test
     * @throws \ReflectionException
     */
    public function Every_demon_parameter_class_has_its_test()
    {
        foreach ($this->getDemonParameterClasses() as $demonParameterClass) {
            $expectedTestClass = str_replace('DrdPlus\\', 'DrdPlus\\Tests\\', $demonParameterClass) . 'Test';
            self::assertTrue(class_exists($expectedTestClass), "Missing test for $demonParameterClass, expected $expectedTestClass");
        }
    }

    /**
     * @param string $rootClass
     * @return array|string[]|AbstractCode[]
     * @throws \ReflectionException
     */
    private function getDemonParameterClasses(string $rootClass = DemonCapacity::class): array
    {
        $codeReflection = new \ReflectionClass($rootClass);
        $rootDir = dirname($codeReflection->getFileName());
        $rootNamespace = $codeReflection->getNamespaceName();
        return $this->scanForDemonClasses($rootDir, $rootNamespace);
    }

    /**
     * @param string $rootDir
     * @param string $rootNamespace
     * @return array|string[]
     * @throws \ReflectionException
     */
    private function scanForDemonClasses(string $rootDir, string $rootNamespace): array
    {
        $demonClasses = [];
        foreach (scandir($rootDir, SCANDIR_SORT_NONE) as $folder) {
            $folderFullPath = $rootDir . DIRECTORY_SEPARATOR . $folder;
            if ($folder !== '.' && $folder !== '..') {
                if (is_dir($folderFullPath)) {
                    foreach ($this->scanForDemonClasses($folderFullPath, $rootNamespace . '\\' . $folder) as $foundCode) {
                        $demonClasses[] = $foundCode;
                    }
                } elseif (is_file($folderFullPath) && preg_match('~(?<classBasename>\w+(?:Code)?)\.php$~', $folder, $matches)) {
                    $className = $rootNamespace . '\\' . $matches['classBasename'];
                    if (is_a($className, \Throwable::class, true)) {
                        continue;
                    }
                    $reflectionClass = new \ReflectionClass($className);
                    if (!$reflectionClass->isAbstract() && !$reflectionClass->isInterface() && !$reflectionClass->isInterface()) {
                        self::assertRegExp(
                            '~\\Demon[[:alpha:]]+$~',
                            $reflectionClass->getName(),
                            'Every single demon parameter should starts by "Demon"'
                        );
                        $demonClasses[] = $reflectionClass->getName();
                    }
                }
            }
        }

        return $demonClasses;
    }
}