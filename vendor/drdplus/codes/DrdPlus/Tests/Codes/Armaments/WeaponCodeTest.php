<?php
namespace DrdPlus\Tests\Codes\Armaments;

use DrdPlus\Codes\Armaments\WeaponCategoryCode;
use DrdPlus\Codes\Armaments\WeaponCode;
use DrdPlus\Codes\Partials\Translatable;

abstract class WeaponCodeTest extends WeaponlikeCodeTest
{
    /**
     * @test
     */
    public function It_is_weapon_code(): void
    {
        /** @var WeaponCode $sut */
        $sut = $this->getSut();
        self::assertInstanceOf(WeaponCode::class, $sut);
        self::assertFalse($sut->isShield());
        self::assertFalse($sut->isProtectiveArmament());
    }

    /**
     * @test
     */
    public function I_can_easily_find_out_if_is_weapon(): void
    {
        /** @var WeaponCode $sut */
        $sut = $this->getSut();
        self::assertTrue($sut->isWeapon());
    }

    /**
     * @test
     */
    public function I_can_easily_find_out_if_is_shield(): void
    {
        /** @var WeaponCode $sut */
        $sut = $this->getSut();
        self::assertFalse($sut->isShield());
    }

    /**
     * @test
     */
    public function I_can_easily_find_out_it_is_not_projectile(): void
    {
        /** @var WeaponCode $sut */
        $sut = $this->getSut();
        self::assertFalse($sut->isProjectile());
    }

    /**
     * @test
     * @runInSeparateProcess
     * @throws \ReflectionException
     */
    public function I_can_extended_it_by_custom_translatable_code(): void
    {
        /** @var WeaponCode $sutClass */
        $sutClass = self::getSutClass();
        $reflectionClass = new \ReflectionClass($sutClass);
        $translations = $reflectionClass->getProperty('translations');
        $translations->setAccessible(true);
        // to reset already initialized translations and force them to be loaded again
        $translations->setValue(null);
        $translations->setAccessible(false);
        self::assertNotContains('foo', $sutClass::getPossibleValues());
        /** like @see \DrdPlus\Codes\Armaments\MeleeWeaponCode::addNewMeleeWeaponCode */
        $addNewCode = 'addNew' . $this->getSutBaseName();
        self::assertTrue($sutClass::$addNewCode('foo', $someCategory = $this->getRandomWeaponCategoryCode(), []));
        self::assertFalse(
            $sutClass::$addNewCode('foo', $someCategory, ['en' => ['one' => 'foo']]),
            'Same custom code to register should be skipped. Have you overloaded getDefaultValues method?'
        );
        self::assertContains('foo', $sutClass::getPossibleValues());
        self::assertTrue(
            $sutClass::$addNewCode('bar', $this->getRandomWeaponCategoryCode(), ['cs' => ['one' => 'taková laťka']])
        );
        self::assertContains('bar', $sutClass::getPossibleValues());
        if ((new \ReflectionClass($sutClass))->isAbstract()) {
            return;
        }
        /** @var Translatable $bar */
        $bar = $sutClass::getIt('bar');
        self::assertSame('taková laťka', $bar->translateTo('cs'));
        self::assertTrue(
            $sutClass::$addNewCode('baz', $this->getRandomWeaponCategoryCode(), ['cs' => ['one' => 'eee, ehm?']])
        );
        $bar = $sutClass::getIt('baz');
        self::assertSame('eee, ehm?', $bar->translateTo('cs'));
    }

    protected function getRandomWeaponCategoryCode(WeaponCategoryCode $differentTo = null): WeaponCategoryCode
    {
        do {
            $category = WeaponCategoryCode::getIt(
                $this->getWeaponCategoryValues()[array_rand($this->getWeaponCategoryValues())]
            );
        } while ($differentTo !== null && $category->getValue() === $differentTo->getValue());

        return $category;
    }

    abstract protected function getWeaponCategoryValues(): array;

    /**
     * @test
     * @runInSeparateProcess
     * @expectedException \DrdPlus\Codes\Partials\Exceptions\InvalidLanguageCode
     * @expectedExceptionMessageRegExp ~a1~
     */
    public function I_can_not_use_invalid_language_code_format_for_custom_code(): void
    {
        /** @var WeaponCode $sutClass */
        $sutClass = self::getSutClass();
        /** like @see \DrdPlus\Codes\Armaments\MeleeWeaponCode::addNewMeleeWeaponCode */
        $addNewCode = 'addNew' . $this->getSutBaseName();
        try {
            $extended = $sutClass::$addNewCode(
                'qux',
                $this->getRandomWeaponCategoryCode(),
                ['cs' => ['one' => 'štěstí']]
            );
        } catch (\Exception $exception) {
            self::fail('No exception expected so far: ' . $exception->getMessage());

            return;
        }
        self::assertTrue($extended, 'Code should not be already registered for this test');
        self::assertTrue(
            $sutClass::$addNewCode('quux', $this->getRandomWeaponCategoryCode(), ['a1' => 'anything here']),
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
        /** like @see \DrdPlus\Codes\Armaments\MeleeWeaponCode::addNewMeleeWeaponCode */
        $addNewCode = 'addNew' . $this->getSutBaseName();
        /** @var WeaponCode $sutClass */
        $sutClass = self::getSutClass();
        self::assertTrue(
            $sutClass::$addNewCode('foobar', $this->getRandomWeaponCategoryCode(), ['uk' => 'this should be array']),
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
        /** like @see \DrdPlus\Codes\Armaments\MeleeWeaponCode::addNewMeleeWeaponCode */
        $addNewCode = 'addNew' . $this->getSutBaseName();
        /** @var WeaponCode $sutClass */
        $sutClass = self::getSutClass();
        self::assertTrue(
            $sutClass::$addNewCode(
                'foobaz',
                $this->getRandomWeaponCategoryCode(),
                ['cs' => ['all' => 'have I missed something?']]
            ),
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
        /** like @see \DrdPlus\Codes\Armaments\MeleeWeaponCode::addNewMeleeWeaponCode */
        $addNewCode = 'addNew' . $this->getSutBaseName();
        /** @var WeaponCode $sutClass */
        $sutClass = self::getSutClass();
        self::assertTrue(
            $sutClass::$addNewCode('fooqux', $this->getRandomWeaponCategoryCode(), ['cs' => ['one' => null]]),
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
        /** like @see \DrdPlus\Codes\Armaments\MeleeWeaponCode::addNewMeleeWeaponCode */
        $addNewCode = 'addNew' . $this->getSutBaseName();
        /** @var WeaponCode $sutClass */
        $sutClass = self::getSutClass();
        self::assertTrue(
            $sutClass::$addNewCode('barfoo', $this->getRandomWeaponCategoryCode(), ['cs' => ['one' => '']]),
            'Code should not be already registered for this test'
        );
    }

    abstract public function I_can_not_extended_it_by_same_code_but_different_category(): void;

}