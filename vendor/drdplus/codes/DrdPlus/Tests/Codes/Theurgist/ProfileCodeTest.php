<?php declare(strict_types=1);

declare(strict_types = 1);

namespace DrdPlus\Tests\Codes\Theurgist;

use DrdPlus\Codes\Theurgist\ProfileCode;

class ProfileCodeTest extends AbstractTheurgistCodeTest
{
    /**
     * @test
     */
    public function I_can_get_all_codes_at_once_or_by_same_named_constant()
    {
        // I can not, because characters ♀ and ♂ can not be part of constant name but we want them in value
        self::assertFalse(false);
    }

    /**
     * @test
	 * @throws \ReflectionException
     */
    public function I_can_get_all_codes_at_once_or_by_similarly_named_constant()
    {
        $reflection = new \ReflectionClass(self::getSutClass());
        $constants = $reflection->getConstants();
        asort($constants);
        $sutClass = self::getSutClass();
        $values = $sutClass::getPossibleValues();
        sort($values);
        self::assertSame(array_values($constants), $values);
        foreach ($values as $value) {
            $constantName = strtoupper($value);
            self::assertArrayHasKey($constantName, $constants);
            self::assertSame($constants[$constantName], $value);
        }
    }

    /**
     * @test
     */
    public function I_can_ask_it_for_gender_and_get_opposite()
    {
        $lookMars = ProfileCode::getIt(ProfileCode::LOOK_MARS);
        self::assertTrue($lookMars->isMars());
        self::assertFalse($lookMars->isVenus());

        $lookVenus = $lookMars->getWithOppositeGender();
        self::assertSame(ProfileCode::getIt(ProfileCode::LOOK_VENUS), $lookVenus);
        self::assertFalse($lookVenus->isMars());
        self::assertTrue($lookVenus->isVenus());

        self::assertSame($lookMars, $lookVenus->getWithOppositeGender());
    }

    protected function getValuesSameInCzechAndEnglish(): array
    {
        return [ProfileCode::RECEPTOR_MARS, ProfileCode::RECEPTOR_VENUS];
    }

}