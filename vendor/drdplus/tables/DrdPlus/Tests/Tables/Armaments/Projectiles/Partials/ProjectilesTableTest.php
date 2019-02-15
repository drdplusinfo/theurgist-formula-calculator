<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Armaments\Projectiles\Partials;

use DrdPlus\Tables\Armaments\Projectiles\Partials\ProjectilesTable;
use DrdPlus\Tests\Tables\Armaments\Partials\WoundingArmamentsTableTest;

abstract class ProjectilesTableTest extends WoundingArmamentsTableTest
{
    /**
     * @test
     */
    public function I_can_get_header()
    {
        $sutClass = self::getSutClass();
        /** @var ProjectilesTable $shootingArmamentsTable */
        $shootingArmamentsTable = new $sutClass();
        self::assertSame(
            [[$this->getRowHeaderName(), 'offensiveness', 'wounds', 'wounds_type', 'range', 'weight']],
            $shootingArmamentsTable->getHeader()
        );
    }

    /**
     * @return string
     */
    abstract protected function getRowHeaderName(): string;

    /**
     * @test
     * @dataProvider provideValueName
     * @param string $valueName
     * @expectedException \DrdPlus\Tables\Armaments\Exceptions\UnknownProjectile
     * @expectedExceptionMessageRegExp ~egg~
     */
    public function I_can_not_get_value_of_unknown_melee_weapon($valueName)
    {
        $getValueNameOf = $this->assembleValueGetter($valueName);
        $sutClass = self::getSutClass();
        /** @var ProjectilesTable $projectilesTable */
        $projectilesTable = new $sutClass();
        $projectilesTable->$getValueNameOf('egg');
    }

    public function provideValueName()
    {
        return [
            [ProjectilesTable::OFFENSIVENESS],
            [ProjectilesTable::WOUNDS],
            [ProjectilesTable::WOUNDS_TYPE],
            [ProjectilesTable::RANGE],
            [ProjectilesTable::WEIGHT],
        ];
    }

}