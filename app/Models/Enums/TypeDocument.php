<?php

namespace App\Models\Enums;

class TypeDocument extends MyEnum
{
    const DEFAULT = 0;
    const TEXT = 1;
    const IMAGE = 2;
    const VIDEO = 3;

    const PDF = 4;
    CONST WORD = 5;
    CONST EXCEL = 6;
    CONST POWERPOINT = 7;
    CONST OTHER = 99;

    protected static $ICON = [
        self::DEFAULT => "<span class='badge badge-info'>DEFAULT</span>",
        self::TEXT => "<span class='badge badge-info'>TEXT</span>",
        self::IMAGE => "<span class='badge badge-info'>IMAGE</span>",
        self::VIDEO => "<span class='badge badge-info'>VIDEO</span>",
        self::PDF => "<span class='badge badge-info'>PDF</span>",
        self::WORD => "<span class='badge badge-info'>WORD</span>",
        self::EXCEL => "<span class='badge badge-info'>EXCEL</span>",
        self::POWERPOINT=> "<span class='badge badge-info'>POWERPOINT</span>",
        self::OTHER=> "<span class='badge badge-info'>OTHER</span>",
    ];

}
