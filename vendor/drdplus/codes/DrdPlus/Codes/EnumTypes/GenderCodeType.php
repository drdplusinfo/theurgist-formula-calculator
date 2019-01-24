<?php
declare(strict_types=1); 

namespace DrdPlus\Codes\EnumTypes;

use DrdPlus\Codes\GenderCode;

class GenderCodeType extends AbstractCodeType
{
    public const GENDER_CODE = 'gender_code';

    public static function registerSelf(): bool
    {
        parent::registerSelf();

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return static::registerCodeAsSubTypeEnum(GenderCode::class);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::GENDER_CODE;
    }
}