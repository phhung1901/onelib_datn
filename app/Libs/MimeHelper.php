<?php

namespace App\Libs;

class MimeHelper
{
    protected $mimes = [
        'txt' => 'text/plain',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'ppt' => 'application/vnd.ms-powerpoint',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'pptm' => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
        'odt' => 'application/vnd.oasis.opendocument.text',
        'pdf' => 'application/pdf',
//        'xls' => 'application/vnd.ms-excel',
//        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        self::OTHER => "Other",
        self::UNKNOWN => "Unknown"
    ];

    const OTHER = 'OTHER';
    const UNKNOWN = '_';

    public static function getCode($mime){
        return array_search($mime, (new self())->mimes);
    }

    public static function getFullName($code){
        $code = strtoupper($code);
        return (new self())->mimes[$code] ?? null;
    }

    public static function getOptions(){
        return (new self())->mimes;
    }
}
