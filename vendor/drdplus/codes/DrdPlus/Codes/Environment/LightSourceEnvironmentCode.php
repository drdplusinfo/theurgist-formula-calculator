<?php
declare(strict_types=1); 

namespace DrdPlus\Codes\Environment;

use DrdPlus\Codes\Partials\AbstractCode;

/**
 * @method static LightSourceEnvironmentCode getIt($codeValue)
 * @method static LightSourceEnvironmentCode findIt($codeValue)
 */
class LightSourceEnvironmentCode extends AbstractCode
{
    public const OPEN_SPACE_OR_ROOM_IN_DARK_UNDERGROUND = 'open_space_or_room_in_dark_underground';
    public const CORRIDOR_IN_DARK_UNDERGROUND_OR_LIGHT_PLASTERED_ROOM = 'corridor_in_dark_underground_or_light_plastered_room';
    public const LIGHT_PLASTERED_CORRIDOR_OR_ROOM_WITH_NEW_SHINY_PLASTER_OR_COVER_OF_CANDLE_BY_HAND = 'light_plastered_corridor_or_room_with_new_shiny_plaster_or_cover_of_candle_by_hand';
    public const CORRIDOR_WITH_SHINY_NEW_PLASTER = 'corridor_with_shiny_new_plaster';
    public const MIRROR_BEHIND_LIGHT_SOURCE = 'mirror_behind_light_source';
    public const THREE_SIDE_MIRROR_DIRECTING_LIGHT_FORWARD = 'three_side_mirror_directing_light_forward';

    public static function getPossibleValues(): array
    {
        return [
            self::OPEN_SPACE_OR_ROOM_IN_DARK_UNDERGROUND,
            self::CORRIDOR_IN_DARK_UNDERGROUND_OR_LIGHT_PLASTERED_ROOM,
            self::LIGHT_PLASTERED_CORRIDOR_OR_ROOM_WITH_NEW_SHINY_PLASTER_OR_COVER_OF_CANDLE_BY_HAND,
            self::CORRIDOR_WITH_SHINY_NEW_PLASTER,
            self::MIRROR_BEHIND_LIGHT_SOURCE,
            self::THREE_SIDE_MIRROR_DIRECTING_LIGHT_FORWARD,
        ];
    }

}