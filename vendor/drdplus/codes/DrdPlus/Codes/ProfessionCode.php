<?php
declare(strict_types=1); 

namespace DrdPlus\Codes;

use DrdPlus\Codes\Partials\TranslatableCode;

/**
 * @method static ProfessionCode getIt($codeValue)
 * @method static ProfessionCode findIt($codeValue)
 */
class ProfessionCode extends TranslatableCode
{
    public const COMMONER = 'commoner';
    public const FIGHTER = 'fighter';
    public const THIEF = 'thief';
    public const RANGER = 'ranger';
    public const WIZARD = 'wizard';
    public const THEURGIST = 'theurgist';
    public const PRIEST = 'priest';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::COMMONER,
            self::FIGHTER,
            self::THIEF,
            self::RANGER,
            self::WIZARD,
            self::THEURGIST,
            self::PRIEST,
        ];
    }

    protected function fetchTranslations(): array
    {
        return [
            'cs' => [
                self::COMMONER => [self::$ONE => 'obyvatel', self::$FEW => 'obyvatelé', self::$MANY => 'obyvatelů'],
                self::FIGHTER => [self::$ONE => 'bojovník', self::$FEW => 'bojovníci', self::$MANY => 'bojovníků'],
                self::THIEF => [self::$ONE => 'zloděj', self::$FEW => 'zloději', self::$MANY => 'zlodějů'],
                self::RANGER => [self::$ONE => 'hraničář', self::$FEW => 'hraničáři', self::$MANY => 'hraničářů'],
                self::WIZARD => [self::$ONE => 'čaroděj', self::$FEW => 'čarodějové', self::$MANY => 'čarodějů'],
                self::THEURGIST => [self::$ONE => 'theurg', self::$FEW => 'theurgové', self::$MANY => 'theurgů'],
                self::PRIEST => [self::$ONE => 'kněz', self::$FEW => 'kněží', self::$MANY => 'kněží'],
            ],
            'en' => [
                self::COMMONER => [self::$ONE => 'commoner', self::$FEW => 'commoners', self::$MANY => 'commoners'],
                self::FIGHTER => [self::$ONE => 'fighter', self::$FEW => 'fighters', self::$MANY => 'fighters'],
                self::THIEF => [self::$ONE => 'thief', self::$FEW => 'thieves', self::$MANY => 'thieves'],
                self::RANGER => [self::$ONE => 'ranger', self::$FEW => 'rangers', self::$MANY => 'rangers'],
                self::WIZARD => [self::$ONE => 'wizard', self::$FEW => 'wizards', self::$MANY => 'wizards'],
                self::THEURGIST => [self::$ONE => 'theurgist', self::$FEW => 'theurgists', self::$MANY => 'theurgists'],
                self::PRIEST => [self::$ONE => 'priest', self::$FEW => 'priests', self::$MANY => 'priests'],
            ],
        ];
    }

}