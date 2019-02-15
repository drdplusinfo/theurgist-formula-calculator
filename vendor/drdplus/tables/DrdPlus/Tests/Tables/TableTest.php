<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables;

use Granam\Tests\Tools\TestWithMockery;

abstract class TableTest extends TestWithMockery
{
    /**
     * @test
     * @throws \ReflectionException
     */
    public function I_can_get_to_rules_by_direct_link(): void
    {
        $reflectionClass = new \ReflectionClass(self::getSutClass());
        $docComment = $reflectionClass->getDocComment();
        self::assertNotEmpty(
            $docComment,
            \sprintf(
                'Missing annotation with link to rules for table %s in format %s',
                self::getSutClass(),
                "\n/**\n * @link https://...\n */"
            )
        );
        $expectedDomainByNamespace = $this->getExpectedDomainByNamespace($reflectionClass->getNamespaceName());
        $escapedExpectedDomain = preg_quote($expectedDomainByNamespace, '~');
        self::assertRegExp(<<<REGEXP
~\s+([Ss]ee PPH page \d+(,? ((left|right) column( top| bottom)?|top|bottom)( \(table without name\))?)?, )?@link {$escapedExpectedDomain}/#.+~
REGEXP
            ,
            $docComment,
            'Missing PPH page reference for table ' . self::getSutClass()
        );
    }

    private function getExpectedDomainByNamespace(string $namespace): string
    {
        if (\strpos($namespace, '\Theurgist\\') !== false) {
            return 'https://theurg.drdplus.info';
        }
        return 'https://pph.drdplus.info';
    }
}