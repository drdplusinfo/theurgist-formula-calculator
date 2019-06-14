<?php declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters;

use DrdPlus\Tables\Theurgist\Spells\SpellParameters\DifficultyAddition;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Difficulty;
use Granam\Tests\Tools\TestWithMockery;

class DifficultyTest extends TestWithMockery
{

    /**
     * @test
     * @throws \ReflectionException
     */
    public function I_get_whispered_current_class_as_return_value_for_difficulty_change()
    {
        $reflectionClass = new \ReflectionClass(Difficulty::class);
        $classBaseName = preg_replace('~^.*[\\\](\w+)$~', '$1', Difficulty::class);
        $add = $reflectionClass->getMethod('getWithDifficultyChange');
        self::assertSame($phpDoc = <<<PHPDOC
/**
 * @param int|float|NumberInterface \$difficultyChangeValue
 * @return {$classBaseName}
 */
PHPDOC
            , preg_replace('~ {2,}~', ' ', $add->getDocComment()),
            "Expected:\n$phpDoc\nfor method 'getWithDifficultyChange'"
        );
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\Exceptions\MissingValueForFormulaDifficultyAddition
     * @expectedExceptionMessageRegExp ~123~
     */
    public function I_can_not_create_it_with_invalid_points_to_annotation()
    {
        new Difficulty([123, 456]);
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
        $difficulty = new Difficulty(['0', '1', '78=321']);
        self::assertSame(0, $difficulty->getValue());
        self::assertEquals(new DifficultyAddition('78=321', 0), $difficulty->getDifficultyAddition());
        self::assertSame('0 (0...1 [' . $difficulty->getDifficultyAddition() . '])', (string)$difficulty);
    }

    protected function I_can_create_it_positive()
    {
        $difficulty = new Difficulty(['35689', '356891', '332211']);
        self::assertSame(35689, $difficulty->getValue());
        self::assertEquals(new DifficultyAddition('332211', 0), $difficulty->getDifficultyAddition());
        self::assertSame('35689 (35689...356891 [' . $difficulty->getDifficultyAddition() . '])', (string)$difficulty);
    }

    /**
     * @test
     */
    public function I_can_get_its_clone_changed_by_difficulty_change()
    {
        $original = new Difficulty(['123', '345', '456=789']);
        self::assertSame(0, $original->getDifficultyAddition()->getCurrentAddition());

        $increased = $original->getWithDifficultyChange(456);
        self::assertSame(579, $increased->getValue());
        self::assertSame($original->getDifficultyAddition()->getNotation(), $increased->getDifficultyAddition()->getNotation());
        self::assertSame(456, $increased->getDifficultyAddition()->getCurrentAddition());
        self::assertNotSame($original, $increased);

        $zeroed = $increased->getWithDifficultyChange(-123);
        self::assertSame(0, $zeroed->getValue());
        self::assertNotSame($original, $zeroed);
        self::assertNotSame($original, $increased);
        self::assertSame(-123, $zeroed->getDifficultyAddition()->getCurrentAddition());
        self::assertSame($original->getDifficultyAddition()->getNotation(), $zeroed->getDifficultyAddition()->getNotation());

        $decreased = $zeroed->getWithDifficultyChange(-234);
        self::assertSame(-111, $decreased->getValue());
        self::assertSame($zeroed->getDifficultyAddition()->getNotation(), $decreased->getDifficultyAddition()->getNotation());
        self::assertSame(-234, $decreased->getDifficultyAddition()->getCurrentAddition());
        self::assertNotSame($zeroed, $decreased);
    }

    /**
     * @test
     */
    public function I_can_use_it()
    {
        $zeroMinimalDifficulty = new Difficulty(['0', '65', '12=13']);
        self::assertSame(0, $zeroMinimalDifficulty->getMinimal());
        self::assertSame(65, $zeroMinimalDifficulty->getMaximal());
        self::assertEquals(new DifficultyAddition('12=13', 0), $zeroMinimalDifficulty->getDifficultyAddition());
        self::assertSame('0 (0...65 [0 {12=>13}])', (string)$zeroMinimalDifficulty);

        $sameMinimalAsMaximal = new Difficulty(['89', '89', '1=2']);
        self::assertSame(89, $sameMinimalAsMaximal->getMinimal());
        self::assertSame(89, $sameMinimalAsMaximal->getMaximal());
        self::assertSame('89 (89...89 [0 {1=>2}])', (string)$sameMinimalAsMaximal);

        $withoutAdditionByRealms = new Difficulty(['123', '456', '0']);
        self::assertSame(123, $withoutAdditionByRealms->getMinimal());
        self::assertSame(456, $withoutAdditionByRealms->getMaximal());
        self::assertSame('123 (123...456 [0 {1=>0}])', (string)$withoutAdditionByRealms);

        $simplyZero = new Difficulty(['0', '0', '0']);
        self::assertSame(0, $simplyZero->getMinimal());
        self::assertSame(0, $simplyZero->getMaximal());
        self::assertSame('0 (0...0 [0 {1=>0}])', (string)$simplyZero);
    }

    /**
     * @test
     */
    public function I_can_get_current_realms_increment()
    {
        $formulaDifficulty = new Difficulty([1, 5, '2=3']);
        self::assertSame($formulaDifficulty->getValue(), $formulaDifficulty->getMinimal());
        self::assertSame(0, $formulaDifficulty->getCurrentRealmsIncrement());
        $maximalDifficulty = $formulaDifficulty->getWithDifficultyChange(4);
        self::assertSame(5, $maximalDifficulty->getValue());
        self::assertSame(0, $maximalDifficulty->getCurrentRealmsIncrement());
        $higherThanMaximalDifficulty = $formulaDifficulty->getWithDifficultyChange(5);
        self::assertSame(6, $higherThanMaximalDifficulty->getValue());
        self::assertSame(1, $higherThanMaximalDifficulty->getCurrentRealmsIncrement());
        $damnBigDifficulty = $formulaDifficulty->getWithDifficultyChange(999);
        self::assertSame(1000, $damnBigDifficulty->getValue());
        self::assertSame(664 /* 995 over maximal, 3 realms per 2 points */, $damnBigDifficulty->getCurrentRealmsIncrement());
    }

    /**
     * @test
     */
    public function I_get_untouched_instance_as_with_zero_change()
    {
        $formulaDifficulty = new Difficulty([1, 2, '3=4']);
        $beforeChange = serialize($formulaDifficulty);

        $withoutChange = $formulaDifficulty->getWithDifficultyChange(0);
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
        new Difficulty(['-1', '65', '12=13']);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidValueForMaximalDifficulty
     * @expectedExceptionMessageRegExp ~-15~
     */
    public function I_can_not_create_it_with_negative_maximum()
    {
        new Difficulty(['6', '-15', '12=13']);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\MinimalDifficultyCanNotBeGreaterThanMaximal
     * @expectedExceptionMessageRegExp ~12.+11~
     */
    public function I_can_not_create_it_with_lesser_maximum_than_minimum()
    {
        new Difficulty(['12', '11', '12=13']);
    }
}