<?php
declare(strict_types = 1);

namespace DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters;

use DrdPlus\Tables\Theurgist\Spells\SpellParameters\FormulaDifficultyAddition;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\FormulaDifficulty;
use Granam\Tests\Tools\TestWithMockery;

class FormulaDifficultyTest extends TestWithMockery
{

    /**
     * @test
     */
    public function I_get_whispered_current_class_as_return_value_of_set_addition()
    {
        $reflectionClass = new \ReflectionClass(FormulaDifficulty::class);
        $classBaseName = preg_replace('~^.*[\\\](\w+)$~', '$1', FormulaDifficulty::class);
        $add = $reflectionClass->getMethod('createWithChange');
        self::assertSame($phpDoc = <<<PHPDOC
/**
 * @param int|float|NumberInterface \$difficultyChangeValue
 * @return {$classBaseName}
 * @throws \Granam\Integer\Tools\Exceptions\Exception
 */
PHPDOC
            , preg_replace('~ {2,}~', ' ', $add->getDocComment()),
            "Expected:\n$phpDoc\nfor method 'getWithAddition'"
        );
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\Exceptions\MissingValueForFormulaDifficultyAddition
     * @expectedExceptionMessageRegExp ~123~
     */
    public function I_can_not_create_it_with_invalid_points_to_annotation()
    {
        new FormulaDifficulty([123, 456]);
    }

    /**
     * @test
     */
    public function I_can_create_it()
    {
        $this->I_can_create_it_with_zero();
        $this->I_can_create_it_positive();
    }

    protected function I_can_create_it_with_zero()
    {
        $difficulty = new FormulaDifficulty(['0', '1', '78=321']);
        self::assertSame(0, $difficulty->getValue());
        self::assertEquals(new FormulaDifficultyAddition('78=321'), $difficulty->getFormulaDifficultyAddition());
        self::assertSame('0 (0...1 [' . $difficulty->getFormulaDifficultyAddition() . '])', (string)$difficulty);
    }

    protected function I_can_create_it_positive()
    {
        $difficulty = new FormulaDifficulty(['35689', '356891', '332211']);
        self::assertSame(35689, $difficulty->getValue());
        self::assertEquals(new FormulaDifficultyAddition('332211'), $difficulty->getFormulaDifficultyAddition());
        self::assertSame('35689 (35689...356891 [' . $difficulty->getFormulaDifficultyAddition() . '])', (string)$difficulty);
    }

    /**
     * @test
     */
    public function I_can_get_its_clone_changed_by_addition()
    {
        $original = new FormulaDifficulty(['123', '345', '456=789']);
        $increased = $original->createWithChange(456);
        self::assertSame(579, $increased->getValue());
        self::assertSame($original->getFormulaDifficultyAddition()->getNotation(), $increased->getFormulaDifficultyAddition()->getNotation());
        self::assertSame(456, $increased->getFormulaDifficultyAddition()->getCurrentAddition());
        self::assertNotSame($original, $increased);

        $zeroed = $increased->createWithChange(-123);
        self::assertSame(0, $zeroed->getValue());
        self::assertNotSame($original, $zeroed);
        self::assertNotSame($original, $increased);
        self::assertSame(-123, $zeroed->getFormulaDifficultyAddition()->getCurrentAddition());
        self::assertSame($original->getFormulaDifficultyAddition()->getNotation(), $zeroed->getFormulaDifficultyAddition()->getNotation());

        $decreased = $zeroed->createWithChange(-234);
        self::assertSame(-111, $decreased->getValue());
        self::assertSame($zeroed->getFormulaDifficultyAddition()->getNotation(), $decreased->getFormulaDifficultyAddition()->getNotation());
        self::assertSame(-234, $decreased->getFormulaDifficultyAddition()->getCurrentAddition());
        self::assertNotSame($zeroed, $decreased);
    }

    /**
     * @test
     */
    public function I_can_use_it()
    {
        $zeroMinimalDifficulty = new FormulaDifficulty(['0', '65', '12=13']);
        self::assertSame(0, $zeroMinimalDifficulty->getMinimal());
        self::assertSame(65, $zeroMinimalDifficulty->getMaximal());
        self::assertEquals(new FormulaDifficultyAddition('12=13'), $zeroMinimalDifficulty->getFormulaDifficultyAddition());
        self::assertSame('0 (0...65 [0 {12=>13}])', (string)$zeroMinimalDifficulty);

        $sameMinimalAsMaximal = new FormulaDifficulty(['89', '89', '1=2']);
        self::assertSame(89, $sameMinimalAsMaximal->getMinimal());
        self::assertSame(89, $sameMinimalAsMaximal->getMaximal());
        self::assertSame('89 (89...89 [0 {1=>2}])', (string)$sameMinimalAsMaximal);

        $withoutAdditionByRealms = new FormulaDifficulty(['123', '456', '0']);
        self::assertSame(123, $withoutAdditionByRealms->getMinimal());
        self::assertSame(456, $withoutAdditionByRealms->getMaximal());
        self::assertSame('123 (123...456 [0 {1=>0}])', (string)$withoutAdditionByRealms);

        $simplyZero = new FormulaDifficulty(['0', '0', '0']);
        self::assertSame(0, $simplyZero->getMinimal());
        self::assertSame(0, $simplyZero->getMaximal());
        self::assertSame('0 (0...0 [0 {1=>0}])', (string)$simplyZero);
    }

    /**
     * @test
     */
    public function I_can_get_current_realms_increment()
    {
        $formulaDifficulty = new FormulaDifficulty([1, 5, '2=3']);
        self::assertSame($formulaDifficulty->getValue(), $formulaDifficulty->getMinimal());
        self::assertSame(0, $formulaDifficulty->getCurrentRealmsIncrement());
        $maximalDifficulty = $formulaDifficulty->createWithChange(4);
        self::assertSame(5, $maximalDifficulty->getValue());
        self::assertSame(0, $maximalDifficulty->getCurrentRealmsIncrement());
        $higherThanMaximalDifficulty = $formulaDifficulty->createWithChange(5);
        self::assertSame(6, $higherThanMaximalDifficulty->getValue());
        self::assertSame(1, $higherThanMaximalDifficulty->getCurrentRealmsIncrement());
        $damnBigDifficulty = $formulaDifficulty->createWithChange(999);
        self::assertSame(1000, $damnBigDifficulty->getValue());
        self::assertSame(664 /* 995 over maximal, 3 realms per 2 points */, $damnBigDifficulty->getCurrentRealmsIncrement());
    }

    /**
     * @test
     */
    public function I_get_untouched_instance_as_with_zero_change()
    {
        $formulaDifficulty = new FormulaDifficulty([1, 2, '3=4']);
        $beforeChange = serialize($formulaDifficulty);
        $withoutChange = $formulaDifficulty->createWithChange(0);
        self::assertSame($formulaDifficulty, $withoutChange);
        self::assertSame($beforeChange, serialize($formulaDifficulty));
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidValueForMinimalDifficulty
     * @expectedExceptionMessageRegExp ~-1~
     */
    public function I_can_not_create_it_with_negative_minimum()
    {
        new FormulaDifficulty(['-1', '65', '12=13']);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidValueForMaximalDifficulty
     * @expectedExceptionMessageRegExp ~-15~
     */
    public function I_can_not_create_it_with_negative_maximum()
    {
        new FormulaDifficulty(['6', '-15', '12=13']);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\MinimalDifficultyCanNotBeGreaterThanMaximal
     * @expectedExceptionMessageRegExp ~12.+11~
     */
    public function I_can_not_create_it_with_lesser_maximum_than_minimum()
    {
        new FormulaDifficulty(['12', '11', '12=13']);
    }
}