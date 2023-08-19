<?php

namespace App\FiguresDetector;

use App\Libs\StringUtils;

final class Utils
{
    
    public static function isCaptionOfFigure(string $string) {
        $prefix = config('detect_figures.prefix');
        return preg_match("/^((". implode('|', $prefix) .")\s+[\p{N}\p{L}]*([°・。.:：\-)\s]|$))|([\p{N}]{1,3}\W\s(". implode('|', $prefix) .")\W)/ui", $string);
    }
    
    /**
     * @param \ThikDev\PdfParser\Objects\Line[] $lines
     * @param int $i
     *
     * @return \ThikDev\PdfParser\Objects\Line
     */
    public static function fullCaption(array $lines, int $i) {
        $caption = $lines[$i];
        $i = $i + 1;
        while (!empty($lines[$i]) && $lines[$i]->merge_up) {
            $caption = self::merge2Line($caption, $lines[$i]);
            $i++;
        }
        
        return $caption;
    }

    /**
     * @param Entities\Figure $figure
     * @return Entities\Figure
     */
    public static function calculateScoreFigure(Entities\Figure $figure) {
        $rules = config('detect_figures.rules');

        $caption_words_count = StringUtils::wordsCount($figure->caption);

        if ($figure->reliability < 0.6 || $caption_words_count < 2
            || preg_match("/^(" . implode('|', $rules['caption_ignore']) . ")/ui", preg_quote($figure->caption, "/"))
        ) {
            $score = 0;
        } else {
            $score = 40;
            if ($figure->page < $rules['page']) {
                $score -= 10;
            }
            if ($figure->caption_reliability < $rules['reliability']
                || $figure->reliability < $rules['reliability']) {
                $score -= 10;
            }
            if ($caption_words_count < $rules['caption_words_count']['min']
                || $caption_words_count > $rules['caption_words_count']['max']) {
                $score -= 10;
            }
            $image_area = ($figure->position['x-bottom'] - $figure->position['x-top']) * ($figure->position['y-bottom'] - $figure->position['y-top']);
            if ($figure->type == 'Table') {
                if ($image_area < $rules['min_image_area']['table']) {
                    $score -= 10;
                }
            } elseif ($figure->type == 'Figure') {
                if ($image_area < $rules['min_image_area']['figure']) {
                    $score -= 10;
                }
            }

        }
        $figure->score = $score;

        return $figure;
    }

    /**
     * @param Entities\Figure $figure
     * @return Entities\Figure
     */
    public static function limitCaption(Entities\Figure $figure) {
        $caption_len = mb_strlen($figure->caption);
        if($figure->score >= 0 && $caption_len > 85) {
            // cắt đến 256 hoặc gặp ". " sau 85 ký tự
            $end_pos = mb_strpos($figure->caption, ". ", 85);
            $end_pos = $end_pos ? min(256, $end_pos, $caption_len) : 256;
            $figure->caption = mb_substr($figure->caption, 0, $end_pos);
        }

        return $figure;
    }
    
    /**
     * @param \ThikDev\PdfParser\Objects\Line $line1
     * @param \ThikDev\PdfParser\Objects\Line $line2
     *
     * @return \ThikDev\PdfParser\Objects\Line
     */
    public static function merge2Line(\ThikDev\PdfParser\Objects\Line $line1, \ThikDev\PdfParser\Objects\Line $line2) {
        foreach ($line2->components as $component) {
            if ($component instanceof \ThikDev\PdfParser\Objects\Text) {
                $line1->appendText($component);
            }
        }
        
        return $line1;
    }
}