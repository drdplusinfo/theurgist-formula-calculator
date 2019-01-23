<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables;

use Granam\Tests\Tools\TestWithMockery;

abstract class TableTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_get_to_rules_both_by_page_reference_and_direct_link()
    {
        $reflectionClass = new \ReflectionClass(self::getSutClass());
        $docComment = $reflectionClass->getDocComment();
        self::assertNotEmpty(
            $docComment,
            'Missing annotation with PPH reference for table ' . self::getSutClass()
            . " in format \n/**\n * See PPH page ?, @link \n */"
        );
        self::assertRegExp(<<<'REGEXP'
~\s+([Ss]ee PPH page \d+(,? ((left|right) column( top| bottom)?|top|bottom)( \(table without name\))?)?, )?@link https://pph\.drdplus\.info/.+~
REGEXP
            ,
            $docComment,
            'Missing PPH page reference for table ' . self::getSutClass()
        );
    }
}