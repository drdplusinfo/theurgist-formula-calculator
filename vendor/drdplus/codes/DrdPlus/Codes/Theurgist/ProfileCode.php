<?php declare(strict_types=1);

namespace DrdPlus\Codes\Theurgist;

/**
 * @method static ProfileCode getIt($codeValue)
 * @method static ProfileCode findIt($codeValue)
 */
class ProfileCode extends AbstractTheurgistCode
{
    public const BARRIER_VENUS = 'barrier_venus';
    public const BARRIER_MARS = 'barrier_mars';
    public const SPARK_VENUS = 'spark_venus';
    public const SPARK_MARS = 'spark_mars';
    public const RELEASE_VENUS = 'release_venus';
    public const RELEASE_MARS = 'release_mars';
    public const SCENT_VENUS = 'scent_venus';
    public const SCENT_MARS = 'scent_mars';
    public const ILLUSION_VENUS = 'illusion_venus';
    public const ILLUSION_MARS = 'illusion_mars';
    public const RECEPTOR_VENUS = 'receptor_venus';
    public const RECEPTOR_MARS = 'receptor_mars';
    public const BREACH_VENUS = 'breach_venus';
    public const BREACH_MARS = 'breach_mars';
    public const FIRE_VENUS = 'fire_venus';
    public const FIRE_MARS = 'fire_mars';
    public const GATE_VENUS = 'gate_venus';
    public const GATE_MARS = 'gate_mars';
    public const MOVEMENT_VENUS = 'movement_venus';
    public const MOVEMENT_MARS = 'movement_mars';
    public const TRANSPOSITION_VENUS = 'transposition_venus';
    public const TRANSPOSITION_MARS = 'transposition_mars';
    public const DISCHARGE_VENUS = 'discharge_venus';
    public const DISCHARGE_MARS = 'discharge_mars';
    public const WATCHER_VENUS = 'watcher_venus';
    public const WATCHER_MARS = 'watcher_mars';
    public const LOOK_VENUS = 'look_venus';
    public const LOOK_MARS = 'look_mars';
    public const TIME_VENUS = 'time_venus';
    public const TIME_MARS = 'time_mars';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::BARRIER_VENUS,
            self::BARRIER_MARS,
            self::SPARK_VENUS,
            self::SPARK_MARS,
            self::RELEASE_VENUS,
            self::RELEASE_MARS,
            self::SCENT_VENUS,
            self::SCENT_MARS,
            self::ILLUSION_VENUS,
            self::ILLUSION_MARS,
            self::RECEPTOR_VENUS,
            self::RECEPTOR_MARS,
            self::BREACH_VENUS,
            self::BREACH_MARS,
            self::FIRE_VENUS,
            self::FIRE_MARS,
            self::GATE_VENUS,
            self::GATE_MARS,
            self::MOVEMENT_VENUS,
            self::MOVEMENT_MARS,
            self::TRANSPOSITION_VENUS,
            self::TRANSPOSITION_MARS,
            self::DISCHARGE_VENUS,
            self::DISCHARGE_MARS,
            self::WATCHER_VENUS,
            self::WATCHER_MARS,
            self::LOOK_VENUS,
            self::LOOK_MARS,
            self::TIME_VENUS,
            self::TIME_MARS,
        ];
    }

    /**
     * @return bool
     */
    public function isMars(): bool
    {
        return strpos($this->getValue(), 'mars') !== false;
    }

    /**
     * @return bool
     */
    public function isVenus(): bool
    {
        return strpos($this->getValue(), 'venus') !== false;
    }

    /**
     * @return ProfileCode
     */
    public function getWithOppositeGender(): ProfileCode
    {
        if (strpos($this->getValue(), 'mars') !== false) {
            return static::getIt(str_replace('mars', 'venus', $this->getValue()));
        }

        return static::getIt(str_replace('venus', 'mars', $this->getValue()));
    }

    protected function fetchTranslations(): array
    {
        $translations = [
            'cs' => [
                'one' => [
                    self::BARRIER_VENUS => 'bariéra ♀',
                    self::BARRIER_MARS => 'bariéra ♂',
                    self::SPARK_VENUS => 'jiskra ♀',
                    self::SPARK_MARS => 'jiskra ♂',
                    self::RELEASE_VENUS => 'uvolnění ♀',
                    self::RELEASE_MARS => 'uvolnění ♂',
                    self::SCENT_VENUS => 'pach ♀',
                    self::SCENT_MARS => 'pach ♂',
                    self::ILLUSION_VENUS => 'iluze ♀',
                    self::ILLUSION_MARS => 'iluze ♂',
                    self::RECEPTOR_VENUS => 'receptor ♀',
                    self::RECEPTOR_MARS => 'receptor ♂',
                    self::BREACH_VENUS => 'průraz ♀',
                    self::BREACH_MARS => 'průraz ♂',
                    self::FIRE_VENUS => 'oheň ♀',
                    self::FIRE_MARS => 'oheň ♂',
                    self::GATE_VENUS => 'brána ♀',
                    self::GATE_MARS => 'brána ♂',
                    self::MOVEMENT_VENUS => 'pohyb ♀',
                    self::MOVEMENT_MARS => 'pohyb ♂',
                    self::TRANSPOSITION_VENUS => 'transpozice ♀',
                    self::TRANSPOSITION_MARS => 'transpozice ♂',
                    self::DISCHARGE_VENUS => 'výboj ♀',
                    self::DISCHARGE_MARS => 'výboj ♂',
                    self::WATCHER_VENUS => 'hlídač ♀',
                    self::WATCHER_MARS => 'hlídač ♂',
                    self::LOOK_VENUS => 'vzhled ♀',
                    self::LOOK_MARS => 'vzhled ♂',
                    self::TIME_VENUS => 'čas ♀',
                    self::TIME_MARS => 'čas ♂',
                ],
            ],
            'en' => ['one' => []],
        ];
        foreach (self::getPossibleValues() as $key) {
            $translation = str_replace(['venus', 'mars', '_'], ['♀', '♂', ' '], $key);
            $translations['en']['one'][$key] = $translation;
        }

        return $translations;
    }

}