<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Armaments\Partials;

use DrdPlus\Codes\Armaments\WeaponCategoryCode;
use Granam\String\StringTools;

abstract class WeaponlikeTableTest extends WoundingArmamentsTableTest
{
    protected function assembleAddNewMethod(): string
    {
        return StringTools::assembleMethodName(
            rtrim(
                str_replace(
                    ['knives', 's_and_', '_and_'], ['knife', '_or_', '_or_'],
                    $this->getWeaponCategory()->getValue()
                ),
                's'
            ),
            'addNew'
        );
    }

    private $weaponCategory;

    protected function getWeaponCategory(): WeaponCategoryCode
    {
        if ($this->weaponCategory === null) {
            $sutClass = static::getSutClass();
            $basename = preg_replace('~^.+\\\([^\\\]+)$~', '$1', $sutClass);
            $keyword = preg_replace('~Table$~', '', $basename);
            $categoryName = StringTools::camelCaseToSnakeCasedBasename($keyword);

            $this->weaponCategory = WeaponCategoryCode::getIt($categoryName);
        }

        return $this->weaponCategory;
    }
}