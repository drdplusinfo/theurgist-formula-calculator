<?php
declare(strict_types=1);
/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Tests\Properties\Base\Partials;

use DrdPlus\Properties\Partials\AbstractIntegerProperty;
use DrdPlus\Tests\Properties\Base\AbstractStoredPropertyTest;
use Granam\Tools\ValueDescriber;

abstract class AbstractIntegerPropertyTest extends AbstractStoredPropertyTest
{
    /**
     * @return int[]
     */
    protected function getValuesForTest(): array
    {
        return [0, 123456];
    }

    /**
     * @test
     * @dataProvider provideSomeArguments
     * @param $justSomeArgument
     * @param $justAnotherArgument
     */
    public function I_can_add_value($justSomeArgument, $justAnotherArgument): void
    {
        /** @var AbstractIntegerProperty $propertyClass */
        $propertyClass = self::getSutClass();
        /** @var AbstractIntegerProperty $property */
        $property = $propertyClass::getIt(123);
        $expectedChangeBy = [
            'name' => 'i can add value',
            'with' => \implode(
                ',',
                [ValueDescriber::describe($justSomeArgument), ValueDescriber::describe($justAnotherArgument)]
            ),
        ];
        $expectedPropertyHistory = [
            [
                'changeBy' => $expectedChangeBy,
                'result' => $property->getValue(),
            ],
        ];
        self::assertEquals($expectedPropertyHistory, $property->getHistory());
        /** @var AbstractIntegerProperty $anotherProperty */
        $anotherProperty = $propertyClass::getIt(123);
        self::assertNotSame($property, $anotherProperty, 'New instance should be created to avoid history share');
        $changedAnotherProperty = $anotherProperty->add(112233);
        self::assertNotEquals($property->getHistory(), $changedAnotherProperty->getHistory(), 'History should not be shared');

        $greater = $property->add(456);
        $expectedGreaterHistory = $expectedPropertyHistory;
        $expectedGreaterHistory[] = [
            'changeBy' => $expectedChangeBy,
            'result' => $greater->getValue(),
        ];
        self::assertEquals($expectedGreaterHistory, $greater->getHistory());
        self::assertSame(123, $property->getValue());
        self::assertNotEquals($property, $greater);
        self::assertSame(579, $greater->getValue());

        $double = $greater->add($greater);
        $expectedDoubleHistoryChange = [
            'changeBy' => $expectedChangeBy,
            'result' => $double->getValue(),
        ];
        $expectedDoubleHistory = $expectedGreaterHistory;
        $expectedDoubleHistory[] = $expectedDoubleHistoryChange;
        self::assertEquals($expectedDoubleHistory, $double->getHistory());
        self::assertSame(1158, $double->getValue());
    }

    public function provideSomeArguments(): array
    {
        return [
            ['foo', new \DateTime()],
        ];
    }

    /**
     * @test
     * @dataProvider provideSomeArguments
     * @param $justSomeArgument
     * @param $justAnotherArgument
     */
    public function I_can_subtract_value($justSomeArgument, $justAnotherArgument): void
    {
        /** @var AbstractIntegerProperty $propertyClass */
        $propertyClass = self::getSutClass();
        /** @var AbstractIntegerProperty $property */
        $property = $propertyClass::getIt(123);
        $expectedChangeBy = [
            'name' => 'i can subtract value',
            'with' => \implode(
                ',',
                [ValueDescriber::describe($justSomeArgument), ValueDescriber::describe($justAnotherArgument)]
            ),
        ];
        $expectedPropertyHistory = [
            [
                'changeBy' => $expectedChangeBy,
                'result' => $property->getValue(),
            ],
        ];
        self::assertEquals($expectedPropertyHistory, $property->getHistory());

        $lesser = $property->sub(456);
        self::assertSame(123, $property->getValue());
        self::assertNotEquals($property, $lesser);
        self::assertSame(-333, $lesser->getValue());
        $expectedLesserHistoryChange = [
            'changeBy' => $expectedChangeBy,
            'result' => $lesser->getValue(),
        ];
        $expectedLesserHistory = $expectedPropertyHistory;
        $expectedLesserHistory[] = $expectedLesserHistoryChange;
        self::assertEquals($expectedLesserHistory, $lesser->getHistory());

        $zero = $lesser->sub($lesser);
        $expectedLesserHistoryChange = [
            'changeBy' => $expectedChangeBy,
            'result' => $zero->getValue(),
        ];
        $expectedZeroHistory = $expectedLesserHistory;
        $expectedZeroHistory[] = $expectedLesserHistoryChange;
        self::assertEquals($expectedZeroHistory, $zero->getHistory());
        self::assertSame(0, $zero->getValue());
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function Has_modifying_methods_return_value_annotated(): void
    {
        $reflectionClass = new \ReflectionClass(self::getSutClass());
        $classBasename = \str_replace($reflectionClass->getNamespaceName() . '\\', '', $reflectionClass->getName());
        self::assertContains(<<<ANNOTATION
 * @method static {$classBasename} getIt(int | \\Granam\\Integer\\IntegerInterface \$value)
 * @method {$classBasename} add(int | \\Granam\\Integer\\IntegerInterface \$value)
 * @method {$classBasename} sub(int | \\Granam\\Integer\\IntegerInterface \$value)
ANNOTATION
            , $reflectionClass->getDocComment());
    }
}