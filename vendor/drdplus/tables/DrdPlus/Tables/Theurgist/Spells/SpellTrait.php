<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Theurgist\Spells;

use DrdPlus\Codes\Theurgist\SpellTraitCode;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\DifficultyChange;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Trap;
use Granam\Strict\Object\StrictObject;
use Granam\Tools\ValueDescriber;

class SpellTrait extends StrictObject
{
    /** @var SpellTraitCode */
    private $spellTraitCode;
    /** @var SpellTraitsTable */
    private $spellTraitsTable;
    /** @var int */
    private $trapPropertyChange;

    /**
     * @param SpellTraitCode $spellTraitCode
     * @param SpellTraitsTable $spellTraitsTable
     * @param int|null $spellTraitTrapPropertyValue current trap value (change will be calculated from that and default trap value)
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\CanNotChangeNotExistingTrap
     */
    public function __construct(
        SpellTraitCode $spellTraitCode,
        SpellTraitsTable $spellTraitsTable,
        int $spellTraitTrapPropertyValue = null
    )
    {
        $this->spellTraitCode = $spellTraitCode;
        $this->spellTraitsTable = $spellTraitsTable;
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

    /**
     * @return DifficultyChange
     * @throws \Granam\Integer\Tools\Exceptions\Exception
     */
    public function getDifficultyChange(): DifficultyChange
    {
        $difficultyChange = $this->spellTraitsTable->getDifficultyChange($this->getSpellTraitCode());
        $trap = $this->getCurrentTrap();
        if (!$trap) {
            return $difficultyChange;
        }

        return $difficultyChange->add($trap->getAdditionByDifficulty()->getCurrentDifficultyIncrement());
    }

    /**
     * @return SpellTraitCode
     */
    public function getSpellTraitCode(): SpellTraitCode
    {
        return $this->spellTraitCode;
    }

    /**
     * @return Trap|null
     */
    public function getBaseTrap(): ?Trap
    {
        return $this->spellTraitsTable->getTrap($this->getSpellTraitCode());
    }

    /**
     * @return Trap|null
     * @throws \Granam\Integer\Tools\Exceptions\Exception
     */
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