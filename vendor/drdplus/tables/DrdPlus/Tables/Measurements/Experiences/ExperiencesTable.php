<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\Measurements\Experiences;

use DrdPlus\Tables\Partials\AbstractTable;
use DrdPlus\Tables\Measurements\Wounds\Wounds;
use DrdPlus\Tables\Measurements\Wounds\WoundsBonus;
use DrdPlus\Tables\Measurements\Wounds\WoundsTable;

/**
 * See PPH page 44 right column top, @link https://pph.drdplus.info/#postup_na_vyssi_uroven
 */
class ExperiencesTable extends AbstractTable
{
    /** @var \DrdPlus\Tables\Measurements\Wounds\WoundsTable */
    private $woundsTable;

    public function __construct(WoundsTable $woundsTable)
    {
        // experiences have similar conversions as wounds have
        $this->woundsTable = $woundsTable;
    }

    /**
     * @return \string[][]
     */
    public function getIndexedValues(): array
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->woundsTable->getIndexedValues();
    }

    /**
     * @return array|\string[][]
     */
    protected function getRowsHeader(): array
    {
        return $this->woundsTable->getRowsHeader();
    }

    /**
     * @return array|\string[]
     */
    protected function getColumnsHeader(): array
    {
        return $this->woundsTable->getColumnsHeader();
    }

    /**
     * Gives highest level possible, independently on any previous level.
     *
     * @param Experiences $experiences
     * @return Level
     */
    public function toLevel(Experiences $experiences): Level
    {
        $woundsBonus = $this->toWoundsBonus($experiences);

        return new Level($this->bonusToLevelValue($woundsBonus), $this);
    }

    private function toWoundsBonus(Experiences $experiences)
    {
        $experiencesValue = $experiences->getValue();
        do {
            $woundsBonus = $this->woundsTable->toBonus(
                new Wounds($experiencesValue--, $this->woundsTable)
            );
            /**
             * avoiding standard bonus round-up, which is unacceptable for experiences to level conversion;
             *
             * @see \DrdPlus\Tables\Measurements\Partials\AbstractFileTable::determineBonus
             */
        } while ($woundsBonus->getWounds()->getValue() > $experiences->getValue());

        return $woundsBonus;
    }

    private function bonusToLevelValue(WoundsBonus $woundsBonus)
    {
        /** @see calculation on PPH page 44 top right */
        $levelValue = $woundsBonus->getValue() - 15;
        if ($levelValue >= 1) {
            return $levelValue;
        }

        return 1;
    }

    /**
     * Leveling sequentially from very first level up to highest possible until all experiences are spent.
     *
     * @param Experiences $experiences
     * @return Level
     */
    public function toTotalLevel(Experiences $experiences): Level
    {
        $currentExperiences = 0;
        $usedExperiences = 0;
        $maxLevelValue = 0;
        while ($usedExperiences + $currentExperiences <= $experiences->getValue()) {
            $level = $this->toLevel(new Experiences($currentExperiences, $this));
            if ($maxLevelValue < $level->getValue()) {
                $usedExperiences += $currentExperiences;
                $maxLevelValue = $level->getValue();
            }
            $currentExperiences++;
        }

        return new Level($maxLevelValue, $this);
    }

    /**
     * Casting level to experiences is mostly lossy conversion!
     * Gives experiences needed from previous (current -1) to given level.
     *
     * @param Level $level
     * @return Experiences
     */
    public function toExperiences(Level $level): Experiences
    {
        if ($level->getValue() > 1) {
            $woundsBonus = new WoundsBonus($this->levelToBonusValue($level), $this->woundsTable);
            $wounds = $this->woundsTable->toWounds($woundsBonus);
            $experiencesValue = $wounds->getValue();
        } else {
            $experiencesValue = 0; // including first level which is for free for main profession
        }

        return new Experiences($experiencesValue, $this);
    }

    private function levelToBonusValue(Level $level)
    {
        /** @see calculation on PPH page 44 top right */
        return $level->getValue() + 15;
    }

    /**
     * Casting level to experiences is mostly lossy conversion!
     * Gives all experiences needed to achieve all levels sequentially up to given.
     *
     * @param Level $level
     * @return Experiences
     */
    public function toTotalExperiences(Level $level): Experiences
    {
        $experiencesSum = 0;
        for ($levelValueToCast = $level->getValue(); $levelValueToCast > 0; $levelValueToCast--) {
            if ($levelValueToCast > 1) { // main profession has first level for free
                $currentLevel = new Level($levelValueToCast, $this);
                $experiencesSum += $currentLevel->getExperiences()->getValue();
            }
        }

        return new Experiences($experiencesSum, $this);
    }

}