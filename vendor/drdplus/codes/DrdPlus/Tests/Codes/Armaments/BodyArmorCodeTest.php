<?php
namespace DrdPlus\Tests\Codes\Armaments;

use DrdPlus\Codes\Armaments\BodyArmorCode;

class BodyArmorCodeTest extends ArmorCodeTest
{
    /**
     * @test
     */
    public function I_can_ask_it_if_is_helm_or_body_armor()
    {
        $bodyArmorCode = BodyArmorCode::getIt(BodyArmorCode::FULL_PLATE_ARMOR);
        self::assertFalse($bodyArmorCode->isHelm());
        self::assertTrue($bodyArmorCode->isBodyArmor());
    }

    /**
     * @test
     */
    public function I_can_get_it_with_default_value(): void
    {
        $sut = $this->findSut();
        self::assertSame(BodyArmorCode::WITHOUT_ARMOR, $sut->getValue(), 'Expected without armor as a default value');
    }

}