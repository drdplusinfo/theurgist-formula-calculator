<?php declare(strict_types=1);

namespace DrdPlus\Tests\Codes\Partials;

use DrdPlus\Codes\Partials\TranslatableCode;
use DrdPlus\Tests\Codes\AbstractCodeTest;

abstract class TranslatableCodeTest extends AbstractCodeTest
{

    /**
     * @test
     * @throws \ReflectionException
     */
    public function I_can_not_create_code_from_unknown_value()
    {
        $this->expectException(\DrdPlus\Codes\Partials\Exceptions\UnknownValueForCode::class);
        $this->expectExceptionMessageRegExp('~da Vinci~');
        if ((new \ReflectionClass(self::getSutClass()))->isAbstract()) {
            throw new \DrdPlus\Codes\Partials\Exceptions\UnknownValueForCode(
                'Even da Vinci can not create instance from abstract class'
            );
        }
        parent::I_can_not_create_code_from_unknown_value();
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function I_can_use_code_object_as_its_string_value()
    {
        if ((new \ReflectionClass(self::getSutClass()))->isAbstract()) {
            self::assertFalse(false, 'Can not create enum from abstract class');

            return;
        }
        parent::I_can_use_code_object_as_its_string_value();
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function I_can_get_translation_for_few_with_decimal()
    {
        if ((new \ReflectionClass(self::getSutClass()))->isAbstract()) {
            self::assertFalse(false, 'Can not create enum from abstract class');

            return;
        }
        /** @var TranslatableCode $someCode */
        $someCode = $this->getSut();
        $few = $someCode->translateTo('cs', 2);
        $fewDecimal = $someCode->translateTo('cs', 2.1);
        if ($few === $fewDecimal) {
            self::assertFalse(false, 'Current translatable code does not distinguish between few and few with decimals');

            return;
        }
        self::assertNotSame($few, $fewDecimal);
        self::assertSame($this->getExpectedCzechTranslationOfFewDecimal($someCode), $fewDecimal);
    }

    protected function getExpectedCzechTranslationOfFewDecimal(/** @noinspection PhpUnusedParameterInspection */
        TranslatableCode $translatableCode): string
    {
        return ''; // intended for overload
    }
}