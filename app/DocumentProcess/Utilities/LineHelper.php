<?php

namespace App\DocumentProcess\Utilities;

use App\Libs\StringUtils;
use ThikDev\PdfParser\Objects\Line;
use ThikDev\PdfParser\Objects\Text;

class LineHelper
{
    public static function getHeadingLevel(Line $line) {
        $heading_levels = [];
        /** @var Text $component */
        foreach ($line->components as $component) {
            if ($component->v_pos == Text::V_POS_NORMAL) {
                if (empty($heading_levels[$component->heading_level])) {
                    $heading_levels[$component->heading_level] = strlen($component->text);
                } else {
                    $heading_levels[$component->heading_level] += strlen($component->text);
                }
            }
        }

        arsort($heading_levels);
        return array_key_first($heading_levels);
    }

    public static function isUpperText(Line $line) {
        /** @todo: sử dụng regex */
        if (!$line->text) return false;
        if (!StringUtils::isLatin($line->text)) return false;
        if (mb_strtoupper($line->text) === $line->text) return true;
        return false;
    }

    public static function isBold(Line $line) {
        $bold_count = 0;
        /** @var Text $component */
        foreach ($line->components as $component) {
            if ($component->bold)  $bold_count++;
        }

        return $bold_count/count($line->components) > 0.5;
    }

    public static function merge2Line(Line $line1, Line $line2) {
        foreach ($line2->components as $component) {
            if ($component instanceof \ThikDev\PdfParser\Objects\Text) {
                $line1->appendText($component);
            }
        }

        return $line1;
    }
}