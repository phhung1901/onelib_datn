<?php
namespace App\Models\Enums;


use MyCLabs\Enum\Enum;

abstract class MyEnum extends Enum
{
    public static function toOptions() : array {
        return array_flip(self::toArray());
    }

    protected static $ICON = [];

    public static function getIcon($enum) {
        return static::$ICON[$enum] ?? null;
    }
}
