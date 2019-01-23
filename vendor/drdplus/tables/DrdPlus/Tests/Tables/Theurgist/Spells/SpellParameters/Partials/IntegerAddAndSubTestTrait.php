<?php
declare(strict_types = 1);

namespace DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters\Partials;

/**
 * @method static getSutClass
 * @method static assertSame($expected, $current, $message = '')
 */
trait IntegerAddAndSubTestTrait
{

    /**
     * @test
     */
    public function I_get_whispered_current_class_as_return_value_of_add_and_sub()
    {
        $reflectionClass = new \ReflectionClass(self::getSutClass());
        $classBaseName = preg_replace('~^.*[\\\](\w+)$~', '$1', self::getSutClass());
        $add = $reflectionClass->getMethod('add');
        $sub = $reflectionClass->getMethod('sub');
        if (strpos($add->getDocComment(), $classBaseName) !== false && strpos($sub->getDocComment(), $classBaseName) !== false) {
            self::assertSame($phpDoc = <<<PHPDOC
/**
 * @param int|float|NumberInterface \$value
 * @return {$classBaseName}
 * @throws \Granam\Integer\Tools\Exceptions\Exception
 */
PHPDOC
                , preg_replace('~ {2,}~', ' ', $add->getDocComment()),
                "Expected:\n$phpDoc\nfor method 'add'"
            );
            self::assertSame($phpDoc = <<<PHPDOC
/**
 * @param int|float|NumberInterface \$value
 * @return {$classBaseName}
 * @throws \Granam\Integer\Tools\Exceptions\Exception
 */
PHPDOC
                , preg_replace('~ {2,}~', ' ', $sub->getDocComment()),
                "Expected:\n$phpDoc\nfor method 'sub'"
            );
        } else {
            self::assertSame($phpDoc = <<<PHPDOC
/**
 * @method {$classBaseName} add(\$value)
 * @method {$classBaseName} sub(\$value)
 */
PHPDOC
                , $reflectionClass->getDocComment(),
                "Expected:\n$phpDoc"
            );
        }
    }
}