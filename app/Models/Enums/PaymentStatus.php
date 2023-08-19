<?php

namespace App\Models\Enums;

class PaymentStatus extends MyEnum
{
    const SUCCESS = 1;
    const ERROR = 0;

    protected static $ICON = [
        self::SUCCESS => "<span class='badge badge-success'>SUCCESS</span>",
        self::ERROR => "<span class='badge badge-error'>ERROR</span>",
    ];
}
