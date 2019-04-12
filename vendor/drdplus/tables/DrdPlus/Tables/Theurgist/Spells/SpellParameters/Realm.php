<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Theurgist\Spells\SpellParameters;

use Granam\Integer\IntegerInterface;
use Granam\Integer\PositiveIntegerObject;
use Granam\Integer\Tools\Exceptions\PositiveIntegerCanNotBeNegative;
use Granam\String\StringInterface;

/**
 * @method Realm add($value)
 * @method Realm sub($value)
 */
class Realm extends PositiveIntegerObject
{
    /**
     * @param int|IntegerInterface|StringInterface $value
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\RealmCanNotBeNegative
     * @throws \DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\RealmIsToHigh
     */
    public function __construct($value)
    {
        try {
            parent::__construct($value, true, false);
        } catch (PositiveIntegerCanNotBeNegative $positiveIntegerCanNotBeNegative) {
            throw new Exceptions\RealmCanNotBeNegative(sprintf('Got %d, expected at least 0', $value));
        }
        if ($this->getValue() > 21) {
            throw new Exceptions\RealmIsToHigh(sprintf('Got %d, expected at most 21', $this->getValue()));
        }
    }
}