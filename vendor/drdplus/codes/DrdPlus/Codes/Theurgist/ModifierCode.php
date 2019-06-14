<?php declare(strict_types=1);

namespace DrdPlus\Codes\Theurgist;

/**
 * @method static ModifierCode getIt($codeValue)
 * @method static ModifierCode findIt($codeValue)
 */
class ModifierCode extends AbstractTheurgistCode
{
    public const COLOR = 'color';
    public const GATE = 'gate';
    public const EXPLOSION = 'explosion';
    public const FILTER = 'filter';
    public const WATCHER = 'watcher';
    public const THUNDER = 'thunder';
    public const INTERACTIVE_ILLUSION = 'interactive_illusion';
    public const HAMMER = 'hammer';
    public const CAMOUFLAGE = 'camouflage';
    public const INVISIBILITY = 'invisibility';
    public const MOVEMENT = 'movement';
    public const BREACH = 'breach';
    public const RECEPTOR = 'receptor';
    public const STEP_TO_FUTURE = 'step_to_future';
    public const STEP_TO_PAST = 'step_to_past';
    public const TRANSPOSITION = 'transposition';
    public const RELEASE = 'release';
    public const FRAGRANCE = 'fragrance';

    /**
     * @return array|\string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::COLOR,
            self::GATE,
            self::EXPLOSION,
            self::FILTER,
            self::WATCHER,
            self::THUNDER,
            self::INTERACTIVE_ILLUSION,
            self::HAMMER,
            self::CAMOUFLAGE,
            self::INVISIBILITY,
            self::MOVEMENT,
            self::BREACH,
            self::RECEPTOR,
            self::STEP_TO_FUTURE,
            self::STEP_TO_PAST,
            self::TRANSPOSITION,
            self::RELEASE,
            self::FRAGRANCE,
        ];
    }

    protected function fetchTranslations(): array
    {
        return [
            'cs' => [
                'one' => [
                    self::COLOR => 'barva',
                    self::GATE => 'brána',
                    self::EXPLOSION => 'exploze',
                    self::FILTER => 'filtr',
                    self::WATCHER => 'hlídač',
                    self::THUNDER => 'hrom',
                    self::INTERACTIVE_ILLUSION => 'interaktivní iluze',
                    self::HAMMER => 'kladivo',
                    self::CAMOUFLAGE => 'maskování',
                    self::INVISIBILITY => 'neviditelnost',
                    self::MOVEMENT => 'pohyb',
                    self::BREACH => 'průraz',
                    self::RECEPTOR => 'receptor',
                    self::STEP_TO_FUTURE => 'schod do budoucnosti',
                    self::STEP_TO_PAST => 'schod do minulosti',
                    self::TRANSPOSITION => 'transpozice',
                    self::RELEASE => 'uvolnění',
                    self::FRAGRANCE => 'vůně',
                ],
            ],
        ];
    }

}