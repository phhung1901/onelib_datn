<?php

return [
    'tika' => [
        'host' => env('TIKA_HOST', 'localhost'),
        'port' => env('TIKA_PORT', 9998),
        'timeout' => env('TIKA_TIMEOUT', 15),
    ],
    'ai_figure_detector' => [
        'host' => env('AI_FIGURE_DETECTOR_HOST', '118.70.13.36:6050'),
        'token' => env('AI_FIGURE_DETECTOR_TOKEN', '123dok2120')
    ],
];
