<?php
declare(strict_types=1);

namespace DrdPlus\Codes\Skills;

use DrdPlus\Codes\Partials\TranslatableCode;

/**
 * @method static SkillTypeCode getIt($codeValue)
 * @method static SkillTypeCode findIt($codeValue)
 */
class SkillTypeCode extends TranslatableCode
{
    public const PHYSICAL = 'physical';
    public const PSYCHICAL = 'psychical';
    public const COMBINED = 'combined';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [self::PHYSICAL, self::PSYCHICAL, self::COMBINED];
    }

    protected function fetchTranslations(): array
    {
        return [
            'cs' => [
                self::PHYSICAL => [self::$ONE => 'fyzický'],
                self::PSYCHICAL => [self::$ONE => 'psychický'],
                self::COMBINED => [self::$ONE => 'kombinovaný'],
            ],
        ];
    }

}