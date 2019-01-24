<?php
namespace DrdPlus\Tests\Codes\Partials;

use DrdPlus\Codes\Partials\Translatable;
use DrdPlus\Codes\Partials\TranslatableExtendableCode;

abstract class TranslatableExtendableCodeTest extends TranslatableCodeTest
{
    /**
     * @test
     * @throws \ReflectionException
     */
    public function Method_to_get_default_values_is_not_public(): void
    {
        $sutClass = self::getSutClass();
        $reflectionClass = new \ReflectionClass($sutClass);
        $getDefaultValues = $reflectionClass->getMethod('getDefaultValues');
        self::assertFalse($getDefaultValues->isPublic(), "Method $sutClass::getDefaultValues is not intended to be public");
    }

    /**
     * @test
     * @runInSeparateProcess
     * @throws \ReflectionException
     */
    public function I_can_extended_it_by_custom_translatable_code(): void
    {
        /** @var TranslatableExtendableCode $sutClass */
        $sutClass = self::getSutClass();
        $reflectionClass = new \ReflectionClass($sutClass);
        $translations = $reflectionClass->getProperty('translations');
        $translations->setAccessible(true);
        // to reset already initialized translations and force them to be loaded again
        $translations->setValue(null);
        $translations->setAccessible(false);
        self::assertNotContains('foo', $sutClass::getPossibleValues());
        /** like @see \DrdPlus\Codes\Armaments\ArrowCode::addNewArrowCode */
        $addNewCode = 'addNew' . $this->getSutBaseName();
        self::assertTrue($sutClass::$addNewCode('foo', []));
        self::assertFalse(
            $sutClass::$addNewCode('foo', ['en' => ['one' => 'foo']]),
            'Same custom code to register should be skipped. Have you overloaded getDefaultValues method?'
        );
        self::assertContains('foo', $sutClass::getPossibleValues());
        self::assertTrue($sutClass::$addNewCode('bar', ['cs' => ['one' => 'taková laťka']]));
        self::assertContains('bar', $sutClass::getPossibleValues());
        if ((new \ReflectionClass($sutClass))->isAbstract()) {
            return;
        }
        /** @var Translatable $bar */
        $bar = $sutClass::getIt('bar');
        self::assertSame('taková laťka', $bar->translateTo('cs'));
        self::assertTrue($sutClass::$addNewCode('baz', ['cs' => ['one' => 'eee, ehm?']]));
        $bar = $sutClass::getIt('baz');
        self::assertSame('eee, ehm?', $bar->translateTo('cs'));
    }

    protected function getSutBaseName(): string
    {
        return preg_replace('~^.+\\\([^\\\]+$)~', '$1', static::getSutClass());
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function All_public_constants_can_be_given_by_getter(): void
    {
        $reflection = new \ReflectionClass(self::getSutClass());
        $constants = $reflection->getConstants();
        self::assertEquals($constants, \array_unique($constants));
        \asort($constants);
        $sutClass = self::getSutClass();
        $possibleValues = $sutClass::getPossibleValues();
        self::assertEquals($possibleValues, \array_unique($possibleValues), 'Possible values should be unique');
        \sort($possibleValues);
        self::assertSame(
            [],
            \array_diff(\array_values($constants), $possibleValues),
            'Some constants are missing in possible values: ' . \implode(',', \array_diff(\array_values($constants), $possibleValues))
        );
        $possibleValuesAndConstantsDifference = \array_diff($possibleValues, \array_values($constants));
        $reflectionClass = new \ReflectionClass(TranslatableExtendableCode::class);
        $customValuesReflection = $reflectionClass->getProperty('customValues');
        $customValuesReflection->setAccessible(true);
        $customValues = $customValuesReflection->getValue()[$sutClass] ?? [];
        \sort($possibleValuesAndConstantsDifference);
        \sort($customValues);
        self::assertEquals(
            $possibleValuesAndConstantsDifference,
            $customValues,
            'That is strange, have you overloaded getDefaultValues method?'
        );
        foreach ($possibleValues as $value) {
            if (\in_array($value, $customValues, true)) { // custom values are not as constants
                continue;
            }
            $constantName = \strtoupper($value);
            self::assertArrayHasKey($constantName, $constants);
            self::assertSame($constants[$constantName], $value);
        }
    }

    /**
     * @test
     * @runInSeparateProcess
     * @expectedException \DrdPlus\Codes\Partials\Exceptions\InvalidLanguageCode
     * @expectedExceptionMessageRegExp ~a1~
     */
    public function I_can_not_use_invalid_language_code_format_for_custom_code(): void
    {
        /** @var TranslatableExtendableCode $sutClass */
        $sutClass = self::getSutClass();
        /** like @see \DrdPlus\Codes\Armaments\ArrowCode::addNewArrowCode */
        $addNewCode = 'addNew' . $this->getSutBaseName();
        try {
            $extended = $sutClass::$addNewCode('qux', ['cs' => ['one' => 'štěstí']]);
        } catch (\Exception $exception) {
            self::fail('No exception expected so far: ' . $exception->getMessage());

            return;
        }
        self::assertTrue($extended, 'Code should not be already registered for this test');
        self::assertTrue(
            $sutClass::$addNewCode('quux', ['a1' => 'anything here']),
            'Code should not be already registered for this test'
        );
    }

    /**
     * @test
     * @runInSeparateProcess
     * @expectedException \DrdPlus\Codes\Partials\Exceptions\InvalidTranslationFormat
     * @expectedExceptionMessageRegExp ~this should be array~
     */
    public function I_can_not_use_invalid_data_format_of_translations_for_custom_code(): void
    {
        /** like @see \DrdPlus\Codes\Armaments\ArrowCode::addNewArrowCode */
        $addNewCode = 'addNew' . $this->getSutBaseName();
        /** @var TranslatableExtendableCode $sutClass */
        $sutClass = self::getSutClass();
        self::assertTrue(
            $sutClass::$addNewCode('foobar', ['uk' => 'this should be array']),
            'Code should not be already registered for this test'
        );
    }

    /**
     * @test
     * @runInSeparateProcess
     * @expectedException \DrdPlus\Codes\Partials\Exceptions\UnknownTranslationPlural
     * @expectedExceptionMessageRegExp ~all~
     */
    public function I_can_not_use_invalid_plural_for_translation_of_custom_code(): void
    {
        /** like @see \DrdPlus\Codes\Armaments\ArrowCode::addNewArrowCode */
        $addNewCode = 'addNew' . $this->getSutBaseName();
        /** @var TranslatableExtendableCode $sutClass */
        $sutClass = self::getSutClass();
        self::assertTrue(
            $sutClass::$addNewCode('foobaz', ['cs' => ['all' => 'have I missed something?']]),
            'Code should not be already registered for this test'
        );
    }

    /**
     * @test
     * @runInSeparateProcess
     * @expectedException \DrdPlus\Codes\Partials\Exceptions\InvalidTranslationFormat
     * @expectedExceptionMessageRegExp ~NULL~
     */
    public function I_can_not_use_non_string_for_translation_of_custom_code(): void
    {
        /** like @see \DrdPlus\Codes\Armaments\ArrowCode::addNewArrowCode */
        $addNewCode = 'addNew' . $this->getSutBaseName();
        /** @var TranslatableExtendableCode $sutClass */
        $sutClass = self::getSutClass();
        self::assertTrue(
            $sutClass::$addNewCode('fooqux', ['cs' => ['one' => null]]),
            'Code should not be already registered for this test'
        );
    }

    /**
     * @test
     * @runInSeparateProcess
     * @expectedException \DrdPlus\Codes\Partials\Exceptions\InvalidTranslationFormat
     * @expectedExceptionMessageRegExp ~''~
     */
    public function I_can_not_use_empty_string_for_translation_of_custom_code(): void
    {
        /** like @see \DrdPlus\Codes\Armaments\ArrowCode::addNewArrowCode */
        $addNewCode = 'addNew' . $this->getSutBaseName();
        /** @var TranslatableExtendableCode $sutClass */
        $sutClass = self::getSutClass();
        self::assertTrue(
            $sutClass::$addNewCode('barfoo', ['cs' => ['one' => '']]),
            'Code should not be already registered for this test'
        );
    }

    /**
     * @test
     */
    public function It_uses_parent_values_as_default_if_not_overloaded(): void
    {
        self::assertSame([], TranslatableExtendableCode::getPossibleValues());
    }
}