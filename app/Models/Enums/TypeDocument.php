<?php

namespace App\Models\Enums;

class TypeDocument extends MyEnum
{
    const TXT = 'txt';

    const DOC = 'doc';
    const DOCX = 'docx';
    const PPT = 'ppt';
    const PPTX = 'pptx';
    const PPTM = 'pptm';
    const ODT = 'odt';
    const PDF = 'pdf';

//    const XLS = 'xls';
//    const XLSX = 'xlsx';
    const OTHER = 'other';

    protected static $ICON = [
        self::TXT => "<span class='badge badge-info'>TXT</span>",
        self::PDF => "<span class='badge badge-info'>PDF</span>",
        self::DOC => "<span class='badge badge-info'>DOC</span>",
        self::DOCX => "<span class='badge badge-info'>DOCX</span>",
        self::PPT => "<span class='badge badge-info'>PPT</span>",
        self::PPTX => "<span class='badge badge-info'>PPTX</span>",
        self::PPTM => "<span class='badge badge-info'>PPTM</span>",
//        self::XLS => "<span class='badge badge-info'>XLS</span>",
//        self::XLSX => "<span class='badge badge-info'>XLSX</span>",
        self::ODT => "<span class='badge badge-info'>ODT</span>",
        self::OTHER => "<span class='badge badge-info'>OTHER</span>",
    ];

}
