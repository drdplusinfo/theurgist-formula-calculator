<?php declare(strict_types = 1);

namespace DrdPlus\Tables\Theurgist\Spells\SpellParameters;

use DrdPlus\Codes\Theurgist\AffectionPeriodCode;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\GetParameterNameTrait;
use Granam\Integer\NegativeInteger;
use Granam\Integer\Tools\ToInteger;
use Granam\Strict\Object\StrictObject;
use Granam\Tools\ValueDescriber;

class RealmsAffection extends StrictObject implements NegativeInteger
{
    use GetParameterNameTrait;

    /**
     * @var int
     */
    private $value;
    /**
     * @var AffectionPeriodCode
     */
    private $affectionPeriodCode;

    /**
     * @param array $affectionParts
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatForNegativeCastingParameter
     */
    public function __construct(array $affectionParts)
    {
        try {
            $this->value = ToInteger::toNegativeInteger($affectionParts[0] ?? null);
        } catch (\Granam\Integer\Tools\Exceptions\Exception $exception) {
            throw new Exceptions\InvalidFormatForNegativeCastingParameter(
                'Expected negative integer, got '
                . (array_key_exists(0, $affectionParts)
                    ? ValueDescriber::describe($affectionParts[0])
                    : 'nothing'
                ) . ' for ' . $this->getParameterName()
            );
        }
        $this->affectionPeriodCode = AffectionPeriodCode::getIt($affectionParts[1] ?? AffectionPeriodCode::DAILY);
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getAffectionPeriodCode(): AffectionPeriodCode
    {
        return $this->affectionPeriodCode;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getValue()
            . ($this->getValue() !== 0
                ? " {$this->getAffectionPeriodCode()}"
                : ''
            );
    }

}