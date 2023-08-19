<?php

namespace App\DocumentProcess\Utilities;

use App\DocumentProcess\FigureExporter\Entities\Figure;
use App\Libs\StringUtils;

class FigureHelper
{
    private static array $prefix = [
        //Anh //India //Philippines
        'table', 'tab.', 'figure', 'fig', 'fig.', 'diagram', 'graphic', 'illustration',
        //Indo
        'tabel', 'grafik', 'gambar', 'ilustrasi',
        //Tây Ba Nha
        'tabla', 'figura', 'cuadro', 'gráfico', 'ilustración',
        //Bồ Đào Nha
        'tabela', 'ilustração',
        //Đức
        'tafel', 'tabelle', 'figur', 'diagramm', 'grafik', 'abbildung', 'abb.',
        //Pháp
        'tableau', 'diagramme', 'graphique',
        //Thổ Nhĩ Kỳ - Turkish
        'tablo' , 'figür', 'ġekil', 'şekil', 'çizelge',
        //Balan - Polish
        'tablica', 'wykres', 'rysunek', 'fot.',
        //Thủy Điển - Swedish
        'tablå', 'tabell',
        //Italy
        'tabella', 'diagramma', 'illustrazione',
        //Hà Lan
        'figuur', 'grafisch', 'illustratie',
        //Nhật Bản //Trung quốc
        '表', '図', '図表', '図[\p{N}]', '圖',
        //Hàn
        '표', '\[표', '\<표', '그림', '\[그림', '\<그림',
        //Nga
        'tаблица',
        //Hungary
        'táblázat', 'ábra', 'térkép', 'grafikon', 'kép',
        // Slovenia
        'tabela', 'grafikon', 'zemljevid', 'graf', 'slika',
        // Phần lan
        'taulukko', 'kaavio', 'kartta', 'kaavio', 'kuva',
        // CH Séc
        'tabulka', 'figura', 'mapa', 'graf', 'obrázek',
        // Na uy
        'tabell', 'figur', 'kart', 'graf', 'bilde',
        // Đan mạch
        'tabel', 'figur', 'kort', 'graf', 'billede',
        //Malay
        'jadual', 'carta pai', 'rajah', 'graf', 'gambar', 'foto',
        //Philippines,
        'talahanayan', 'graph', 'pigura', 'fig.', 'ilustrasyon', 'blg.', 'larawan',
        //việt nam
        'bảng', 'hình', 'hỡnh', 'đồ thị', 'sơ đồ',
    ];

    private static $rules = [
        'page' => 10,
        'reliability' => 0.9,
        'min_image_area' => [
            'table' => 240*240,
            'figure' => 300*300,
        ],
        'caption_words_count' => [
            'min' => 10,
            'max' => 20,
        ],
        'caption_ignore' => [
            'table of content', 'table of contents', 'table of figures', 'table index', 'figure index', //Anh
            'DAFTAR ISI', 'DAFTAR TABEL', 'DAFTAR GAMBAR', 'gambar judul halaman', 'tabel judul halaman', //Indo
            'tabla de contenidos', //Tây Ba Nha
            'tabela de conteúdo', //Bồ Đào Nha,
            'table des matières', 'table des matieres', //Pháp
            //Đức
            'ISI KANDUNGAN', 'SENARAI JADUAL', //Malay
            'MGA NILALAMAN', //Ph
            'DANH MỤC HÌNH', 'MỤC LỤC', //Viet Nam
        ],
    ];

    public static function isCaptionOfFigure(string $string): bool
    {
        return preg_match("/^((". implode('|', self::$prefix) .")\s+[\p{N}\p{L}]*([°・。.:：\-)\s]|$))|([\p{N}]{1,3}\W\s(". implode('|', self::$prefix) .")\W)/ui", $string);
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
            $caption = LineHelper::merge2Line($caption, $lines[$i]);
            $i++;
        }

        return $caption;
    }

    public static function getScoreFigure(Figure $figure): int
    {
        $caption_words_count = StringUtils::wordsCount($figure->caption);

        if ($figure->reliability < 0.6 || $caption_words_count < 2
            || preg_match("/^(" . implode('|', self::$rules['caption_ignore']) . ")/ui", preg_quote($figure->caption, "/"))
        ) {
            $score = 0;
        } else {
            $score = 40;
            if ($figure->page < self::$rules['page']) {
                $score -= 10;
            }
            if ($figure->caption_reliability < self::$rules['reliability']
                || $figure->reliability < self::$rules['reliability']) {
                $score -= 10;
            }
            if ($caption_words_count < self::$rules['caption_words_count']['min']
                || $caption_words_count > self::$rules['caption_words_count']['max']) {
                $score -= 10;
            }
            $image_area = ($figure->position['x-bottom'] - $figure->position['x-top']) * ($figure->position['y-bottom'] - $figure->position['y-top']);
            if ($figure->type == 'Table') {
                if ($image_area < self::$rules['min_image_area']['table']) {
                    $score -= 10;
                }
            } elseif ($figure->type == 'Figure') {
                if ($image_area < self::$rules['min_image_area']['figure']) {
                    $score -= 10;
                }
            }

        }
        return $score;

    }

    public static function limitCaption(Figure $figure): string
    {
        $caption_len = mb_strlen($figure->caption);
        if($figure->score >= 0 && $caption_len > 85) {
            // cắt đến 256 hoặc gặp ". " sau 85 ký tự
            $end_pos = mb_strpos($figure->caption, ". ", 85);
            $end_pos = $end_pos ? min(256, $end_pos, $caption_len) : 256;
            return mb_substr($figure->caption, 0, $end_pos);
        }

        return $figure->caption;
    }
}