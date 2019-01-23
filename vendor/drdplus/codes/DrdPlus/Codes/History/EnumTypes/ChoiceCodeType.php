<?php
declare(strict_types=1); 

namespace DrdPlus\Codes\History\EnumTypes;

use DrdPlus\Codes\EnumTypes\AbstractCodeType;
use DrdPlus\Codes\History\ChoiceCode;

class ChoiceCodeType extends AbstractCodeType
{
    public const CHOICE_CODE = 'choice_code';

    public static function registerSelf(): bool
    {
        parent::registerSelf();

        return static::registerCodeAsSubTypeEnum(ChoiceCode::class);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::CHOICE_CODE;
    }
}