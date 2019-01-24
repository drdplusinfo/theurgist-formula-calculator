<?php
declare(strict_types=1);
/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Tests\Properties\Base;

/**
 * @method static string getSutClass
 * @method static assertContains($needle, $haystack)
 * @method static fail($message)
 */
trait ItHasProperlyAnnotatedCodeGetter
{
    /**
     * @test
     * @throws \ReflectionException
     */
    public function It_has_properly_annotated_code_getter(): void
    {
        $sutReflection = new \ReflectionClass(self::getSutClass());
        $requiredAnnotation = ' * @return PropertyCode';
        if ($sutReflection->hasMethod('getCode')
            && \strpos($sutReflection->getMethod('getCode')->getDocComment(), $requiredAnnotation)
        ) {
            self::assertContains($requiredAnnotation, $sutReflection->getMethod('getCode')->getDocComment());

            return;
        }

        $requiredAnnotation = '* @method PropertyCode getCode()';
        $interfaceReflections = [$sutReflection];
        do {
            $newInterfaceReflections = [];
            /** @var \ReflectionClass $interfaceReflection */
            foreach ($interfaceReflections as $interfaceReflection) {
                if (\strpos($interfaceReflection->getDocComment(), $requiredAnnotation)) {
                    self::assertContains($requiredAnnotation, $interfaceReflection->getDocComment());

                    return;
                }
                foreach ($interfaceReflection->getInterfaces() as $newInterfaceReflection) {
                    $newInterfaceReflections[$newInterfaceReflection->getName()] = $newInterfaceReflection;
                }
            }
            $interfaceReflections = $newInterfaceReflections;
        } while (\count($interfaceReflections) > 0);

        do {
            if (\strpos($sutReflection->getDocComment(), $requiredAnnotation)) {
                self::assertContains($requiredAnnotation, $sutReflection->getDocComment());

                return;
            }
        } while ($sutReflection = $sutReflection->getParentClass());

        self::fail('Not ' . self::getSutClass() . ' nor any of its parents has expected return value annotation ' . $requiredAnnotation);
    }
}