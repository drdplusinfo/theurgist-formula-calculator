<?php declare(strict_types=1);

namespace DrdPlus\Tables\Theurgist\Spells;

use DrdPlus\Codes\Theurgist\SpellTraitCode;
use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\DifficultyChange;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Trap;
use Granam\Strict\Object\StrictObject;
use Granam\Tools\ValueDescriber;

class SpellTrait extends StrictObject
{
    /** @var SpellTraitCode */
    private $spellTraitCode;
    /** @var Tables */
    private $tables;
    /** @var int */
    private $trapPropertyChange;

    /**
     * @param SpellTraitCode $spellTraitCode
     * @param Tables $tables
     * @param int|null $spellTraitTrapPropertyValue current trap value (change will be calculated from that and default trap value)
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\CanNotChangeNotExistingTrap
     */
    public function __construct(
        SpellTraitCode $spellTraitCode,
        Tables $tables,
        int $spellTraitTrapPropertyValue = null
    )
    {
        $this->spellTraitCode = $spellTraitCode;
        $this->tables = $tables;
        $this->trapPropertyChange = $this->sanitizeSpellTraitTrapPropertyChange($spellTraitTrapPropertyValue);
    }

    /**
     * @param int|null $spellTraitTrapPropertyValue
     * @return int
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\CanNotChangeNotExistingTrap
     */
    private function sanitizeSpellTraitTrapPropertyChange(int $spellTraitTrapPropertyValue = null): int
    {
        if ($spellTraitTrapPropertyValue === null) {
            return 0; // no change
        }
        $baseTrap = $this->getBaseTrap();
        if (!$baseTrap) {
            throw new Exceptions\CanNotChangeNotExistingTrap(
                "Spell trait {$this->getSpellTraitCode()} does not have a trap. Got trap change "
                . ValueDescriber::describe($spellTraitTrapPropertyValue)
            );
        }

        return $spellTraitTrapPropertyValue - $baseTrap->getDefaultValue();
    }

    public function getDifficultyChange(): DifficultyChange
    {
        $difficultyChange = $this->tables->getSpellTraitsTable()->getDifficultyChange($this->getSpellTraitCode());
        $trap = $this->getCurrentTrap();
        if (!$trap) {
            return $difficultyChange;
        }

        return $difficultyChange->add($trap->getAdditionByDifficulty()->getCurrentDifficultyIncrement());
    }

    public function getSpellTraitCode(): SpellTraitCode
    {
        return $this->spellTraitCode;
    }

    public function getBaseTrap(): ?Trap
    {
        return $this->tables->getSpellTraitsTable()->getTrap($this->getSpellTraitCode());
    }

    public function getCurrentTrap(): ?Trap
    {
        $trap = $this->getBaseTrap();
        if (!$trap) {
            return null;
        }

        return $trap->getWithAddition($this->trapPropertyChange);
    }

    public function __toString()
    {
        return $this->getSpellTraitCode()->getValue();
    }
}