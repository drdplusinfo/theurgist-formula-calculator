<?php declare(strict_types=1);

namespace Granam\Tests\WebContentBuilder;

use PHPUnit\Framework\TestCase;

class AllTestsTest extends TestCase
{
    /**
     * @test
     * @throws \ReflectionException
     */
    public function Every_test_has_expected_namespace()
    {
        foreach ($this->getFilesWithTests(__DIR__) as $fileWithTest) {
            $classNamePart = str_replace(__DIR__, '', $fileWithTest);
            $classNamePart = str_replace('/', '\\', $classNamePart);
            $classNamePart = ltrim($classNamePart, '\\');
            $classNamePart = preg_replace('~[.]php$~', '', $classNamePart);
            $expectedClassName = __NAMESPACE__ . '\\' . $classNamePart;
            self::assertTrue(
                class_exists($expectedClassName) || interface_exists($expectedClassName) || trait_exists($expectedClassName),
                "Expected class {$expectedClassName} determined from file {$fileWithTest} not found"
            );
        }
    }

    /**
     * @param string $sourceDir
     * @return array|string[]
     */
    private function getFilesWithTests(string $sourceDir): array
    {
        $testFiles = [];
        foreach (scandir($sourceDir, SCANDIR_SORT_NONE) as $folder) {
            if ($folder === '.' || $folder === '..') {
                continue;
            }
            $folderPath = $sourceDir . '/' . $folder;
            if (is_dir($folderPath)) {
                foreach ($this->getFilesWithTests($folderPath) as $fileFromDir) {
                    $testFiles[] = $fileFromDir;
                }
            } elseif (preg_match('~[.]php$~', $folderPath)) {
                $testFiles[] = $folderPath;
            }
        }
        return $testFiles;
    }
}