<?php
namespace DrdPlus\Tests\Codes;

use DrdPlus\Codes\Code;
use DrdPlus\Codes\Partials\AbstractCode;
use Granam\Tests\Tools\TestWithMockery;

class AllCodesTest extends TestWithMockery
{
    use GetCodeClassesTrait;

    /**
     * @test
     * @throws \ReflectionException
     */
    public function All_of_them_are_code(): void
    {
        foreach ($this->getCodeClasses() as $codeClass) {
            self::assertTrue(
                class_exists($codeClass),
                $codeClass . ' has not been found, check namespace of its test ' . static::class
            );
            self::assertTrue(is_a($codeClass, Code::class, true), $codeClass . ' is not an instance of ' . Code::class);
        }
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function I_can_get_all_codes_at_once_or_by_same_named_constant(): void
    {
        /** @var AbstractCode $codeClass */
        foreach ($this->getCodeClasses() as $codeClass) {
            $reflection = new \ReflectionClass($codeClass);
            $constants = $reflection->getConstants();
            \asort($constants);
            $values = $codeClass::getPossibleValues();
            \sort($values);
            self::assertSame(array_values($constants), $values, 'Expected different possible values from code ' . $codeClass);
            foreach ($values as $value) {
                $constantName = strtoupper($value);
                self::assertArrayHasKey($constantName, $constants);
                self::assertSame($constants[$constantName], $value);
            }
        }
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function All_constants_can_be_given_by_getter(): void
    {
        foreach ($this->getCodeClasses() as $codeClass) {
            $constantValues = (new \ReflectionClass($codeClass))->getConstants();
            \sort($constantValues); // re-index by numbers
            /** @var string[] $givenValues */
            $givenValues = $codeClass::getPossibleValues();
            $expectedIndex = 0;
            foreach ($givenValues as $index => $value) {
                self::assertSame($expectedIndex, $index, 'Indexes of all values should be continual.');
                $expectedIndex++;
            }
            \sort($givenValues);
            self::assertSame(
                $constantValues,
                $givenValues,
                'There are ' . (
                \count($missingOrDifferent = \array_diff($constantValues, $givenValues)) > 0
                    ? "missing values from 'getPossibleValues' " . var_export($missingOrDifferent, true)
                    : "superfluous values from 'getPossibleValues' " . var_export(array_diff($givenValues, $constantValues), true)
                )
            );
        }
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function I_can_create_code_instance_from_every_constant(): void
    {
        /** @var AbstractCode $codeClass */
        foreach ($this->getCodeClasses() as $codeClass) {
            foreach ((new \ReflectionClass($codeClass))->getConstants() as $constant) {
                self::assertTrue($codeClass::hasIt($constant));
                $code = $codeClass::getIt($constant);
                self::assertInstanceOf($codeClass, $code);
                self::assertSame($constant, $code->getValue());
                $sameCode = $codeClass::getIt($constant);
                self::assertSame($code, $sameCode);
            }
        }
    }

    /**
     * @test
     * @expectedException \DrdPlus\Codes\Partials\Exceptions\UnknownValueForCode
     * @expectedExceptionMessageRegExp ~da Vinci~
     * @dataProvider provideCodeClasses
     * @param string $codeClass
     */
    public function I_can_not_create_code_from_unknown_value(string $codeClass): void
    {
        /** @var AbstractCode $codeClass */
        self::assertFalse($codeClass::hasIt('da Vinci'));
        $codeClass::getIt('da Vinci');
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function provideCodeClasses(): array
    {
        return \array_map(
            function (string $className) {
                return [$className];
            },
            $this->getCodeClasses()
        );
    }

    /**
     * @test
     * @expectedException \Granam\ScalarEnum\Exceptions\UnexpectedValueToEnum
     * @expectedExceptionMessageRegExp ~\DateTime~
     * @dataProvider provideCodeClasses
     * @param string $codeClass
     */
    public function I_can_not_create_code_from_invalid_value_format(string $codeClass): void
    {
        /** @var AbstractCode $codeClass */
        $codeClass::getIt(new \DateTime());
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function I_can_use_code_object_as_its_string_value(): void
    {
        foreach ($this->getCodeClasses() as $codeClass) {
            /** @var string[] $givenValues */
            $givenValues = $codeClass::getPossibleValues();
            foreach ($givenValues as $givenValue) {
                self::assertTrue($codeClass::hasIt($givenValue));
                $code = $codeClass::getIt($givenValue);
                self::assertSame($givenValue, (string)$code);
            }
        }
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function I_get_whispered_current_code_as_return_value_of_factory_method(): void
    {
        foreach ($this->getCodeClasses() as $codeClass) {
            $reflectionClass = new \ReflectionClass($codeClass);
            $classBaseName = \preg_replace('~^.*[\\\](\w+)$~', '$1', $codeClass);
            if (\strpos($reflectionClass->getDocComment(), 'getIt') !== false) {
                self::assertContains(<<<PHPDOC
 * @method static {$classBaseName} getIt(\$codeValue)
PHPDOC
                    , $reflectionClass->getDocComment(),
                    "Missing getIt method annotation in $codeClass"
                );
            } else {
                self::assertContains(<<<PHPDOC
 * @return {$classBaseName}|AbstractCode
PHPDOC
                    , \preg_replace('~ +~', ' ', $reflectionClass->getMethod('getIt')->getDocComment()),
                    "Missing getIt method annotation in $codeClass"
                );
            }
            if (\strpos($reflectionClass->getDocComment(), 'findIt') !== false) {
                self::assertContains(<<<PHPDOC
 * @method static {$classBaseName} findIt(\$codeValue)
PHPDOC
                    , $reflectionClass->getDocComment(),
                    "Missing findIt method annotation in $codeClass"
                );
            } else {
                self::assertContains(<<<PHPDOC
 * @return {$classBaseName}|AbstractCode
PHPDOC
                    , \preg_replace('~ +~', ' ', $reflectionClass->getMethod('findIt')->getDocComment()),
                    "Missing findIt method annotation in $codeClass"
                );
            }
        }
    }
}