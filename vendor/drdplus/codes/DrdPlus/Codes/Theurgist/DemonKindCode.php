<?php declare(strict_types=1);

namespace DrdPlus\Codes\Theurgist;

/**
 * @method static DemonKindCode getIt($codeValue)
 * @method static DemonKindCode findIt($codeValue)
 */
class DemonKindCode extends AbstractTheurgistCode
{
    public const BARE = 'bare';
    public const ANIMATING = 'animating';

    public static function getPossibleValues(): array
    {
        return [
            self::BARE,
            self::ANIMATING,
        ];
    }

    protected static function getDefaultValue(): string
    {
        return self::BARE;
    }

    protected function fetchTranslations(): array
    {
        return [
            'cs' => [
                'one' => [
                    self::BARE => 'prostý',
                    self::ANIMATING => 'animační',
                ],
            ],
        ];
    }

}