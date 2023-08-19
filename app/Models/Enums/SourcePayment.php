<?php

namespace App\Models\Enums;

class SourcePayment  extends MyEnum
{
    const VNPAY = "VNPAY";
    const PAYPAL = "PAYPAL";
    const LIBSHARE = "LIBSHARE";

    protected static $ICON = [
        self::VNPAY => "<span class='badge badge-info'>VNPAY</span>",
        self::PAYPAL => "<span class='badge badge-info'>PAYPAL</span>",
        self::LIBSHARE => "<span class='badge badge-info'>LIBSHARE</span>",
    ];
}
