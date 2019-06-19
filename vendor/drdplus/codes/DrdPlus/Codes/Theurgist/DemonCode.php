<?php declare(strict_types=1);

namespace DrdPlus\Codes\Theurgist;

/**
 * @method static DemonCode getIt($codeValue)
 * @method static DemonCode findIt($codeValue)
 */
class DemonCode extends AbstractTheurgistCode
{
    // link catchers
    public const CRON = 'cron';
    public const DEMON_OF_MOVEMENT = 'demon_of_movement';
    public const WARDEN = 'warden';
    // lesser demons
    public const DEMON_OF_MUSIC = 'demon_of_music';
    public const DEMON_DEFENDER = 'demon_defender';
    public const DEMON_GAMBLER = 'demon_gambler';
    public const DEMON_OF_TIRELESSNESS = 'demon_of_tirelessness';
    public const DEMON_OF_OMIT_VOMIT = 'demon_of_omit_vomit';
    public const DEMON_ATTACKER = 'demon_attacker';
    public const DEMON_OF_VISION = 'demon_of_vision';
    public const GOLEM = 'golem';
    public const DEADY = 'deady';
    public const BERSERK = 'berserk';
    // higher demons
    public const IMP = 'imp';
    public const DEMON_OF_KNOWLEDGE = 'demon_of_knowledge';
    public const NAVIGATOR = 'navigator';
    public const GUARDIAN = 'guardian';
    public const SPY = 'spy';

    public static function getPossibleValues(): array
    {
        return [
            self::CRON,
            self::DEMON_OF_MOVEMENT,
            self::WARDEN,
            self::DEMON_OF_MUSIC,
            self::DEMON_DEFENDER,
            self::DEMON_GAMBLER,
            self::DEMON_OF_TIRELESSNESS,
            self::DEMON_OF_OMIT_VOMIT,
            self::DEMON_ATTACKER,
            self::DEMON_OF_VISION,
            self::GOLEM,
            self::DEADY,
            self::BERSERK,
            self::IMP,
            self::DEMON_OF_KNOWLEDGE,
            self::NAVIGATOR,
            self::GUARDIAN,
            self::SPY,
        ];
    }

    protected static function getDefaultValue(): string
    {
        return self::CRON;
    }

    protected function fetchTranslations(): array
    {
        return [
            'cs' => [
                'one' => [
                    self::CRON => 'kron',
                    self::DEMON_OF_MOVEMENT => 'démon pohybu',
                    self::WARDEN => 'správce',
                    self::DEMON_OF_MUSIC => 'démon hudby',
                    self::DEMON_DEFENDER => 'démon obránce',
                    self::DEMON_GAMBLER => 'démon hazardér',
                    self::DEMON_OF_TIRELESSNESS => 'démon neúnavnosti',
                    self::DEMON_OF_OMIT_VOMIT => 'démon odložené šavle',
                    self::DEMON_ATTACKER => 'démon útočník',
                    self::DEMON_OF_VISION => 'démon vidění',
                    self::GOLEM => 'golem',
                    self::DEADY => 'mrtvák',
                    self::BERSERK => 'berserk',
                    self::IMP => 'ďáblík',
                    self::DEMON_OF_KNOWLEDGE => 'démon vědění věcí',
                    self::NAVIGATOR => 'navigátor',
                    self::GUARDIAN => 'strážce',
                    self::SPY => 'špión',
                ],
            ],
        ];
    }

    public function isLinkCatcher(): bool
    {
        return in_array(
            $this->getValue(),
            [self::CRON, self::DEMON_OF_MOVEMENT, self::WARDEN],
            true
        );
    }

    public function isLesserDemon(): bool
    {
        return in_array(
            $this->getValue(),
            [
                self::DEMON_OF_MUSIC,
                self::DEMON_DEFENDER,
                self::DEMON_GAMBLER,
                self::DEMON_OF_TIRELESSNESS,
                self::DEMON_OF_OMIT_VOMIT,
                self::DEMON_ATTACKER,
                self::DEMON_OF_VISION,
                self::GOLEM,
                self::DEADY,
                self::BERSERK,
            ],
            true
        );
    }

    public function isHigherDemon(): bool
    {
        return in_array(
            $this->getValue(),
            [
                self::IMP,
                self::DEMON_OF_KNOWLEDGE,
                self::NAVIGATOR,
                self::GUARDIAN,
                self::SPY,
            ],
            true
        );
    }
}