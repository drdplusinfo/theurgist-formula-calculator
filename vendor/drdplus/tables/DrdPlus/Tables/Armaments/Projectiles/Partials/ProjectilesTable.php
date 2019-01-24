<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\Armaments\Projectiles\Partials;

use DrdPlus\Codes\Armaments\ProjectileCode;
use DrdPlus\Tables\Armaments\Exceptions\UnknownProjectile;
use DrdPlus\Tables\Armaments\Partials\AbstractArmamentsTable;
use DrdPlus\Tables\Armaments\Partials\WoundingArmamentsTable;
use DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound;
use Granam\String\StringInterface;
use Granam\Tools\ValueDescriber;

/**
 * Note: Every projectile considered as with cover of 0 (if you are so despair you have to cover by a sling stone for
 * example).
 * See PPH page 88 right column, @link https://pph.drdplus.info/#tabulka_strelnych_a_vrhacich_zbrani
 */
abstract class ProjectilesTable extends AbstractArmamentsTable implements WoundingArmamentsTable
{
    public const RANGE = 'range';

    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [
            self::OFFENSIVENESS => self::INTEGER,
            self::WOUNDS => self::INTEGER,
            self::WOUNDS_TYPE => self::STRING,
            self::RANGE => self::INTEGER,
            self::WEIGHT => self::FLOAT,
        ];
    }

    protected function getRowsHeader(): array
    {
        return ['projectile'];
    }

    /**
     * @param string|StringInterface $weaponlikeCode
     * @return int
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownProjectile
     */
    public function getOffensivenessOf($weaponlikeCode): int
    {
        return $this->getValueOf($weaponlikeCode, self::OFFENSIVENESS);
    }

    /**
     * @param string|StringInterface|ProjectileCode $projectileCode
     * @param string $valueName
     * @return float|int|string|bool
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownProjectile
     */
    protected function getValueOf($projectileCode, string $valueName)
    {
        try {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $this->getValue([$projectileCode], $valueName);
        } catch (RequiredRowNotFound $exception) {
            throw new UnknownProjectile(
                'Unknown projectile code ' . ValueDescriber::describe((string)$projectileCode)
            );
        }
    }

    /**
     * @param string|StringInterface $weaponlikeCode
     * @return int
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownProjectile
     */
    public function getWoundsOf($weaponlikeCode): int
    {
        return $this->getValueOf($weaponlikeCode, self::WOUNDS);
    }

    /**
     * @param string|StringInterface $weaponlikeCode
     * @return string
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownProjectile
     */
    public function getWoundsTypeOf($weaponlikeCode): string
    {
        return $this->getValueOf($weaponlikeCode, self::WOUNDS_TYPE);
    }

    /**
     * @param string|StringInterface $weaponlikeCode
     * @return int
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownProjectile
     */
    public function getRangeOf($weaponlikeCode): int
    {
        return $this->getValueOf($weaponlikeCode, self::RANGE);
    }

    /**
     * @param string|StringInterface $weaponlikeCode
     * @return float
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownProjectile
     */
    public function getWeightOf($weaponlikeCode): float
    {
        return $this->getValueOf($weaponlikeCode, self::WEIGHT);
    }

}