<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables;

use DrdPlus\Tables\Table;
use PHPUnit\Framework\TestCase;

class EveryTableTest extends TestCase
{
    /**
     * @test
     * @throws \ReflectionException
     */
    public function It_has_table_interface()
    {
        self::assertTrue(method_exists(Table::class, 'getIndexedValues'));
        self::assertTrue(method_exists(Table::class, 'getValues'));
        self::assertTrue(method_exists(Table::class, 'getHeader'));
        foreach (self::getTableClasses() as $tableClass) {
            self::assertTrue(
                is_a($tableClass, Table::class, true),
                sprintf('Table %s does not implements %s interface', $tableClass, Table::class)
            );
        }
    }

    /**
     * @return array|string[]
     * @throws \ReflectionException
     */
    public static function getTableClasses(): array
    {
        $tablesReflection = new \ReflectionClass(Table::class);
        $rootDir = dirname($tablesReflection->getFileName());
        $rootNamespace = $tablesReflection->getNamespaceName();

        return self::scanForTables($rootDir, $rootNamespace);
    }

    /**
     * @param string $rootDir
     * @param string $rootNamespace
     * @return array
     * @throws \ReflectionException
     */
    private static function scanForTables(string $rootDir, string $rootNamespace): array
    {
        $tableClasses = [];
        foreach (scandir($rootDir, SCANDIR_SORT_NONE) as $fileOrDir) {
            $fullPath = $rootDir . DIRECTORY_SEPARATOR . $fileOrDir;
            if ($fileOrDir !== '.' && $fileOrDir !== '..') {
                if (is_dir($fullPath)) {
                    foreach (self::scanForTables($fullPath, $rootNamespace . '\\' . $fileOrDir) as $tableClass) {
                        $tableClasses[] = $tableClass;
                    }
                } else if (is_file($fullPath) && preg_match('~(?<tableBasename>\w+Table)\.php$~', $fileOrDir, $matches)) {
                    $assembledTableClass = $rootNamespace . '\\' . $matches['tableBasename'];
                    self::assertTrue(
                        class_exists($assembledTableClass) || interface_exists($assembledTableClass),
                        "Class {$assembledTableClass} can not be auto-loaded"
                    );
                    $tableReflection = new \ReflectionClass($assembledTableClass);
                    $tableClasses[] = $tableReflection->getName();
                }
            }
        }

        return $tableClasses;
    }
}