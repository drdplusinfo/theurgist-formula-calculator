<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Properties\Base\EnumTypes;

use Doctrineum\Integer\IntegerEnumType;
use DrdPlus\Codes\Properties\PropertyCode;

class IntelligenceType extends IntegerEnumType
{
    public const INTELLIGENCE = PropertyCode::INTELLIGENCE;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::INTELLIGENCE;
    }
}