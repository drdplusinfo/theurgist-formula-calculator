<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Measurements\Experiences;

use DrdPlus\Tables\Measurements\Partials\AbstractBonus;

class Level extends AbstractBonus
{
    public const LEVEL = 'level';
    public const MIN_LEVEL = 0;
    public const MAX_LEVEL = 20;

    /**
     * @var ExperiencesTable
     */
    private $experiencesTable;

    public function __construct($value, ExperiencesTable $experiencesTable)
    {
        parent::__construct($value);
        $this->guardLevelBoundaries($this->getValue());
        $this->experiencesTable = $experiencesTable;
    }

    /**
     * Level is not limited by table values, so has to be in code
     *
     * @param int $levelValue
     * @throws \DrdPlus\Tables\Measurements\Experiences\Exceptions\MinLevelUnderflow
     * @throws \DrdPlus\Tables\Measurements\Experiences\Exceptions\MaxLevelOverflow
     */
    private function guardLevelBoundaries($levelValue)
    {
        if ($levelValue < static::MIN_LEVEL) {
            throw new Exceptions\MinLevelUnderflow(
                'Level has to be at least ' . self::MIN_LEVEL . ', got ' . $levelValue
            );
        }
        if ($levelValue > static::MAX_LEVEL) {
            throw new Exceptions\MaxLevelOverflow(
                'Level can not be greater than ' . self::MAX_LEVEL . ', got ' . $levelValue
            );
        }
    }

    /**
     * @return Experiences
     */
    public function getExperiences(): Experiences
    {
        return $this->experiencesTable->toExperiences($this);
    }

    /**
     * Summary of experiences, needed to achieve current level
     *
     * @return Experiences
     */
    public function getTotalExperiences(): Experiences
    {
        return $this->experiencesTable->toTotalExperiences($this);
    }

}
