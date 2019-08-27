<?php declare(strict_types=1);

namespace DrdPlus\Tests\Codes\Properties;

use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Codes\Properties\RemarkableSenseCode;
use DrdPlus\Tests\Codes\Partials\TranslatableCodeTest;

class PropertyCodeTest extends TranslatableCodeTest
{
    /**
     * @test
     */
    public function I_can_get_base_property_codes()
    {
        self::assertSame(
            [
                'strength',
                'agility',
                'knack',
                'will',
                'intelligence',
                'charisma',
            ],
            PropertyCode::getBasePropertyPossibleValues()
        );
    }

    /**
     * @test
     */
    public function I_can_get_body_property_codes()
    {
        self::assertEquals(
            [
                'age',
                'height_in_cm',
                'height',
                'body_weight_in_kg',
                'body_weight',
                'size',
            ],
            PropertyCode::getBodyPropertyPossibleValues()
        );
    }

    /**
     * @test
     */
    public function I_can_get_derived_property_codes()
    {
        self::assertEquals(
            [
                'beauty',
                'dangerousness',
                'dignity',
                'endurance',
                'fatigue_boundary',
                'senses',
                'speed',
                'toughness',
                'wound_boundary',
                'movement_speed',
                'maximal_load',
            ],
            PropertyCode::getDerivedPropertyPossibleValues()
        );
    }

    /**
     * @test
     */
    public function I_can_get_native_property_codes()
    {
        self::assertEquals(
            [
                'infravision',
                'native_regeneration',
                'remarkable_sense',
            ],
            PropertyCode::getNativePropertyPossibleValues()
        );
    }

    /**
     * @test
     */
    public function I_can_get_remarkable_property_codes()
    {
        self::assertSame(
            RemarkableSenseCode::getPossibleValues(),
            PropertyCode::getRemarkableSensePropertyPossibleValues()
        );
        $remarkableSenseCodeReflection = new \ReflectionClass(RemarkableSenseCode::class);
        foreach ($remarkableSenseCodeReflection->getConstants() as $remarkableSenseCodeConstant => $value) {
            self::assertTrue(defined(PropertyCode::class . '::' . $remarkableSenseCodeConstant));
            self::assertSame($value, constant(PropertyCode::class . '::' . $remarkableSenseCodeConstant));
        }
    }

    /**
     * @test
     */
    public function I_can_get_restriction_property_codes()
    {
        self::assertEquals(
            [
                'requires_dm_agreement',
            ],
            PropertyCode::getRestrictionPropertyPossibleValues()
        );
    }
}