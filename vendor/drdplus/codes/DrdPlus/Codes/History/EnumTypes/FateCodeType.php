<?php
declare(strict_types=1);

namespace DrdPlus\Codes\History\EnumTypes;

use DrdPlus\Codes\EnumTypes\AbstractCodeType;
use DrdPlus\Codes\History\FateCode;

class FateCodeType extends AbstractCodeType
{
    public const FATE_CODE = 'fate_code';

    public static function registerSelf(): bool
    {
        parent::registerSelf();

        return static::registerCodeAsSubTypeEnum(FateCode::class);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::FATE_CODE;
    }
}