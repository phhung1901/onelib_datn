<?php

namespace App\DocumentProcess\Utilities;

use App\Libs\StringUtils;
use App\Libs\StringValidator;

class HeadingHelper
{
    private static array $text_ignored = [
        //Anh //India //Philippines
        'table', 'T ABLE', 'tab.', 'figure', 'fig', 'fig.', 'diagram',
        'graphic', 'graph', 'image', 'illustration', 'schema', 'plano', 'exhibit',
        'photo', 'box', 'listing', 'activity', 'proposition', 'zona', 'lemme',
        //Indo
        'tabel', 'angka', 'grafik', 'gambar', 'ilustrasi',
        //Tây Ba Nha
        'tabla', 'figura', 'imagen', 'cuadro', 'gráfico', 'ilustración', 'fotografía',
        //Bồ Đào Nha
        'tabela', 'ilustração', 'quadro', 'tarefa',
        //Đức
        'tafel', 'tisch', 'tabelle', 'figur', 'diagramm', 'grafik', 'abbildung', 'abb.', 'karte', 'bild', 'gleichung',
        //Pháp
        'tableau', 'diagramme', 'graphique', 'schéma', 'photographie',
        //Thổ Nhĩ Kỳ - Turkish
        'tablo' , 'figür', 'ġekil', 'şekil', 'çizelge', 'resim', 'örnek',
        //Balan - Polish
        'tablica', 'wykres', 'rysunek', 'fot.', 'schemat', 'zagadka',
        //Thủy Điển - Swedish
        'tablå', 'tabell', 'fråga', 'faktaruta',
        //Italy
        'tabella', 'diagramma', 'illustrazione', 'immagine', 'fotografia',
        //Hà Lan
        'figuur', 'grafisch', 'illustratie', 'kaart', 'grafiek', 'tekstbox', 'afbeelding', 'stap',
        // Hungary
        '.*\W\s(táblázat|ábra|térkép|grafikon|kép)\W',
        //Nga
        'tаблица',
        // Slovenia
        'tabela', 'grafikon', 'zemljevid', 'graf', 'slika',
        // Phần Lan
        'taulukko', 'kaavio', 'kartta', 'kaavio', 'kuva',
        // CH Séc
        'tabulka', 'figura', 'mapa', 'graf', 'obrázek',
        // Na uy
        'tabell', 'figur', 'kart', 'graf', 'bilde',
        // Đan mạch
        'tabel', 'figur', 'kort', 'graf', 'billede',
        // Korean
        '그림', '표',
        //Taiwan
        '表', '圖',
        //japan
        '図', '図表',
        //Malay
        'jadual', 'carta pai', 'rajah', 'graf', 'gambar', 'foto', 'FYP FTKW', 'lampiran',
        //Ph
        'talahanayan', 'graph', 'pigura', 'ilustrasyon', 'larawan',
        //Vietnam
        'bảng', 'hình', 'hỡnh', 'ví dụ', 'đồ thị', 'sơ đồ', 'câu', 'đề', 'bước', 'cách', 'hướng',
        //Other
        'e𝑞', 'year', 'month', 'week', 'for', 'kompetensi', 'co 2',
        'example', 'contoh', 'ejemplo', 'exemplo', 'experiment', 'voorbeeld',
        'method',
        'π', 'μ', 'θ', 'ε', 'δ', 'β', 'Σ', '∴', '∵', '♣',
    ];

    private static array $heading_ignored = [
        'reference', 'references', 'referencia', 'Referenz', 'referensi', 'référence', 'referência', 'literatúra', 'referentie', 'referencja', 'referens', 'referans', '參考文獻',
        'RUJUKAN', 'sanggunian',
        'table of content', 'tabla de contenidos', 'tabela de conteúdo', 'table des matières', 'ISI KANDUNGAN',
        'list of figures', 'lista de figuras', 'liste des figures', 'daftar gambar', 'SENARAI JADUAL',
        'tài liệu tham khảo', 'DANH MỤC HÌNH', 'MỤC LỤC', //Viet Nam
    ];

    const HEADING_H2_UPPER_TYPE = 2;
    const HEADING_H2_TYPE = 3;

    private static array $heading_types = [
        [       //V.V.V.V
            'level' => 12,
            'regex' => '/^([IVX]){1,4}(\.([IVX]){1,4}){3}\.?\s*/',
        ],
        [       //V.V.V
            'level' => 11,
            'regex' => '/^([IVX]){1,4}(\.([IVX]){1,4}){2}\.?\s*/',
        ],
        [       //V.V.
            'level' => 10,
            'regex' => '/^([IVX]){1,4}(\.([IVX]){1,4}){1}\.?\s*/',
        ],
        [       //V.1.1.1
            'level' => 15,
            'regex' => '/^([IVX]){1,4}\.\s*([\p{N}]){1,2}(\.([\p{N}]){1,2}){2}(\.|\/)?\s*(?=[\p{L}])/u',
        ],
        [       //V.1.1.
            'level' => 14,
            'regex' => '/^([IVX]){1,4}\.\s*([\p{N}]){1,2}(\.([\p{N}]){1,2}){1}(\.|\/)?\s*(?=[\p{L}])/u',
        ],
        [       //V.1.
            'level' => 13,
            'regex' => '/^([IVX]){1,4}\.\s*([\p{N}]){1,2}[.:\/\s\-)、]{1}\s*(?=[\p{L}])/u',
        ],
        [       //BAB V.
            'level' => 2,
            'regex' => '/^([^\s\W\p{N}]{2,})\s+([IVX]){1,4}([．.:\s\-)、]|$)\s*/u',
        ],
        [       //第一章
            'level' => 2,
            'regex' => '/^第\s?([一二三四五六七八九十\p{N}])\s?章(\s*、?|$)/ui',
        ],
        [       //V.
            'level' => 9,
            'regex' => '/^([IVX]){1,4}[．.:\-)、]+\s*/u',
        ],
        [       //1.1.1.1
            'level' => 19,
            'regex' => '/^([\p{N}]){1,2}(\.([\p{N}]){1,2}){3}(\.|\/|:)?\s*(?=[\p{L}])/u',
        ],
        [       //1.1.1
            'level' => 18,
            'regex' => '/^([\p{N}]){1,2}(\.([\p{N}]){1,2}){2}(\.|\/|:)?\s*(?=[\p{L}])/u',
        ],
        [       //1.1.
            'level' => 17,
            'regex' => '/^([\p{N}]){1,2}(\.([\p{N}]){1,2}){1}(\.|\/|:)?\s*(?=[\p{L}])/u',
        ],
        [       //Bab 1.
            'level' => 3,
            'regex' => '/^([^\s\W\p{N}]{2,})\s+([\p{N}]){1,2}([．.:\s\-)、]|$)\s*/ui',
        ],
        [       //第一節
            'level' => 3,
            'regex' => '/^第\s?([一二三四五六七八九十\p{N}])\s?(節|部)(\s*、?|$)/ui',
        ],
        [       //1.
            'level' => 16,
            'regex' => '/^([\p{N}一二三四五六七八九十]){1,2}[．.:\/\s\-)、]{1}\s+(?=[\p{L}])/u',
        ],
        [       //A.1.1.1.1
            'level' => 8,
            'regex' => '/^([A-Z]\.)\s*([\p{N}]){1,2}(\.([\p{N}]){1,2}){3}(\.|\/)?\s*(?=[\p{L}])/u',
        ],
        [       //A.1.1.1
            'level' => 7,
            'regex' => '/^([A-Z]\.)\s*([\p{N}]){1,2}(\.([\p{N}]){1,2}){2}(\.|\/)?\s*(?=[\p{L}])/u',
        ],
        [       //A.1.1.
            'level' => 6,
            'regex' => '/^([A-Z]\.)\s*([\p{N}]){1,2}(\.([\p{N}]){1,2}){1}(\.|\/)?\s*(?=[\p{L}])/u',
        ],
        [       //A.1.
            'level' => 5,
            'regex' => '/^([A-Z]\.)\s*([\p{N}]){1,2}[.:\/\s\-)、]{1}\s*(?=[\p{L}])/u',
        ],
        [       //A.
            'level' => 4,
            'regex' => '/^[A-Z][．\.:,)\-、]+\s*(?=[\p{L}])/u',
        ],
    ];

    /**
     * Trả về danh sách các loại heading của line hiện tại theo các regex cho trước
     *
     * @param string $heading
     * @return array
     */
    public static function findHeadingType(string $heading)
    {
        $heading = trim($heading);
        $heading = preg_replace("/\s+/u", " ", $heading);

        /** Fix Bug */
        $heading = preg_replace("/^S ECTION/", "SECTION", $heading);
        $heading = preg_replace("/^T ABLE/", "TABLE", $heading);
        /**  */

        foreach (self::$heading_types as ['level' => $level, 'regex' => $regex]) {
            if (preg_match($regex, $heading, $matches)) {
                if ($level == 2 || $level == 3) {
                    if (self::hasIgnoreText($heading))
                        return null;
                    if (StringUtils::isLatin($heading))
                        preg_match('/^([^\s\W\p{N}]+)\s+[^\p{Z}]+([.:\s\-)]|$)\s*/ui', $heading, $matches);
                }

                $prefix = trim($matches[0]);
                $heading = HeadingHelper::standardizedHeading($heading, $prefix);
                return compact('level', 'prefix', 'heading');
            }
        }

        return null;
    }

    public static function hasIgnoreText(string $text): bool
    {
        foreach (self::$text_ignored as $ignored) {
            if (preg_match("/^$ignored/ui", trim($text))) {
                return true;
            }
        }
        return false;
    }

    /**
     * Remove prefix, chuẩn hóa heading
     */
    public static function standardizedHeading(string $line_text, ?string $prefix): string
    {
        $content = StringUtils::trim((str_replace($prefix, '', $line_text)));
        $content = preg_replace("/(\.\s*|\-\s*|\s\s|…\s*|·\s*)\g{1}+\W*[0-9ivx]*\W*$/ui", '', $content);
        $content = preg_replace('/^[^\p{L}\p{N}]+/ui', '', $content);
        $content = StringUtils::trim($content);
        return rtrim($content, ".:");
    }

    public static function validateHeading(?string $string): bool
    {
        if (!$string) return false;

        $title_validate = new StringValidator($string, ['min_words' => 1, 'min_chars' => 4]);
        return $title_validate->isValid();
    }

    /** @todo: Hàm này cần suwar */
    public static function calculateHeadingQuality(?string $heading): int
    {
        if (empty($heading)) return 0;

        if (preg_match("/^(\p{Ll})/u", $heading)) {
            return 50;
        }

        foreach (self::$heading_ignored as $ignore_heading) {
            if (preg_match("/^$ignore_heading/ui", preg_quote($heading))) {
                return 50;
            }
        }

        //heading có dạng "FC FC FC 3.79 FB FB FB ...."
        //4 từ cạnh nhau có ít hơn 3 ký tự
        $x = 0;
        foreach (explode(' ', $heading) as $w) {
            preg_match_all("/[\p{L}\p{Han}\p{Hangul}\p{Hiragana}\p{Katakana}]/ui", $w, $matches);
            $x = (count($matches[0]) < 3) ? $x+1 : 0;
            if ($x == 4) return 0;
        }

        return 100;
    }
}