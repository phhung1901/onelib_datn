<?php

namespace App\Libs;

class MakePath {

    protected const MAX_DIR_DEPTH = 2;

    protected const FORMAT_DATETIME = 'Y-m';

    public static function fromId(int $id, $ext = 'ext') {
        $multiplier = (self::MAX_DIR_DEPTH + 1) * 3 - strlen((string)$id);
        if ($multiplier >= 0) {
            $full = str_repeat("0", $multiplier) . $id;
        } else {
            $full = (string)$id;
        }
        $full = substr($full, 0, -3);
        $path_partials = [];
        for ($i = 1; $i < self::MAX_DIR_DEPTH; $i++) {
            $path_partials[] = substr($full, -3 * $i, 3);
        }
        $path_partials[] = substr($full, 0, strlen($full) - 3 * (self::MAX_DIR_DEPTH - 1));
        $path_partials = array_reverse($path_partials);
        return implode("/", $path_partials) . "/" . $id . ($ext ? "." . $ext : "");
    }

    public static function fromDateTime($ext = 'ext', $p = '', $s = '') {
        $date = date(self::FORMAT_DATETIME);
        $file_name = ($p ? $p . "_" : "") . time(). ($s ? "_" . $s : "") .'.'.$ext;

        return $date . '/' . $file_name;
    }

}
