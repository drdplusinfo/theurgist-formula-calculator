<?php declare(strict_types=1);

namespace DrdPlus\Tests\Codes\Armaments;

use DrdPlus\Codes\Armaments\HelmCode;

class HelmCodeTest extends ArmorCodeTest
{
    /**
     * @test
     */
    public function I_can_ask_it_if_is_helm_or_body_armor()
    {
        $helmCode = HelmCode::getIt(HelmCode::BARREL_HELM);
        self::assertTrue($helmCode->isHelm());
        self::assertFalse($helmCode->isBodyArmor());
    }

    /**
     * @test
     */
    public function I_can_get_it_with_default_value()
    {
        $sut = $this->findSut();
        self::assertSame(HelmCode::WITHOUT_HELM, $sut->getValue(), 'Expected without helm as a default value');
    }

}