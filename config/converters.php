<?php

return [
    'mapping' => [
        'txt' => [
            'pdf' => 'jodconverter',
        ],
        'doc' => [
            'txt' => 'tika',
            'pdf' => 'jodconverter',
        ],
        'docx' => [
            'txt' => 'tika',
            'pdf' => 'jodconverter',
        ],
        'ppt' => [
            'txt' => 'tika',
            'pdf' => 'jodconverter',
        ],
        'pptx' => [
            'txt' => 'tika',
            'pdf' => 'jodconverter',
        ],
        'pptm' => [
            'txt' => 'tika',
            'pdf' => 'jodconverter',
        ],
        'odt' => [
            'txt' => 'jodconverter',
            'pdf' => 'jodconverter',
        ],
        'pdf' => [
            'pdf' => 'qpdf',
            'txt' => 'pdftotext',
            'html' => 'pdftohtml',
            'xml' => 'pdftoxml',
        ],
    ],

    'drivers' => [
        'qpdf' => [
            'path' => env('CONVERTER_BIN_QPDF', env('QPDF_BIN')),
            'options' => []
        ],
        'pdftotext' => [
            'path' => env('CONVERTER_PDFTOTEXT', env('PDFTOTEXT_BIN')),
            'options' => []
        ],
        'pdftohtml' => [
            'path' => env('CONVERTER_PDFTOHTML', env('PDFTOHTML_BIN')),
            'options' => []
        ],
        'pdftoxml' => [
            'path' => env('CONVERTER_PDFTOHTML', env('PDFTOHTML_BIN')),
            'options' => []
        ],
        'tika' => [
            'path' => env('CONVERTER_TIKA_MODE', 'web'),
            'options' => [
                'host' => env('CONVERTER_TIKA_HOST', env('TIKA_HOST', 'localhost')),
                'port' => env('CONVERTER_TIKA_PORT', env('TIKA_PORT', 9998)),
                'timeout' => env('CONVERTER_TIKA_TIMEOUT', env('TIKA_TIMEOUT', 15)),
            ]
        ],
        'jodconverter' => [
            'path' => env('CONVERTER_JODCONVERTER', env('JODCONVERTER_HOST', 'http://localhost:8080')),
            'options' => []
        ],
    ],

    'tmp' => [
        'root' => storage_path('tmp'),
        'timeout' => 10, //minutes
    ],

    'convert_encode' => env('CONVERTER_CONVERT_ENCODE', false),
];
