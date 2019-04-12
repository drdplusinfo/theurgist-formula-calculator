<?php
declare(strict_types = 1);

namespace DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters\Partials;

/**
 * @method static getSutClass
 * @method static assertSame($expected, $current, $message = '')
 */
trait CastingParameterSetAdditionTrait
{

    /**
     * @test
     * @throws \ReflectionException
     */
    public function I_get_whispered_current_class_as_return_value_of_set_addition(): void
    {
        $reflectionClass = new \ReflectionClass(self::getSutClass());
        $classBaseName = preg_replace('~^.*[\\\](\w+)$~', '$1', self::getSutClass());
        $getWithAddition = $reflectionClass->getMethod('getWithAddition');
        if (strpos($getWithAddition->getDocComment(), $classBaseName) !== false) {
            self::assertSame($phpDoc = <<<PHPDOC
/**
 * @param int|float|NumberInterface \$additionValue
 * @return {$classBaseName}|CastingParameter
 */
PHPDOC
                , preg_replace('~ {2,}~', ' ', $getWithAddition->getDocComment()),
                "Expected:\n$phpDoc\nfor method 'getWithAddition'"
            );
        } else {
            self::assertSame($phpDoc = <<<PHPDOC
/**
 * @method {$classBaseName} getWithAddition(\$additionValue)
 */
PHPDOC
                , $reflectionClass->getDocComment(),
                "Expected:\n$phpDoc"
            );
        }
    }
}