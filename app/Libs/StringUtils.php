<?php


namespace App\Libs;


use GuzzleHttp\Exception\RequestException;
use StupidDev\ViEncoder\Encoder\Code;
use StupidDev\ViEncoder\Encoder\Converter;
use StupidDev\ViEncoder\Encoder\Detector;
use StupidDev\ViEncoder\Encoder\EncodeException;

class StringUtils
{
    public static function normalize(?string $string) {
        $string = trim( $string );
        $string = self::unicodeConvert($string);
        $string = mb_strtolower( $string );
        $string = preg_replace( "/\p{Z}+/ui", " ", $string);
        $string = preg_replace( "/[^\p{M}\w\s]+/ui", " ", $string);
        $string = preg_replace( "/\s{2,}/", " ", $string);
        return trim($string);
    }

    public static function wordsCount(string $string) {
        return count(self::extractWords($string));
    }

    public static function countUniqueWords(string $string) {
        return count(self::extractUniqueWords($string));
    }

    public static function charactersCount(string $string) {
        if (preg_match_all("/\p{L}/ui", $string, $matches)) {
            return count($matches[0]);
        }
        return 0;
    }

    public static function extractWords(string $string) {
        $string = preg_replace( "/[\(\[].*[\)\]]/", " ", $string);
        $string = preg_replace( "/[\W\p{Z}\p{N}]/u", " ", $string);
        $string = preg_replace( "/\s{2,}/", " ", $string);

        $latin = $cjk = $hangul = $thai = $arabic = $cyrillic = $devanagari = [];
        if (preg_match_all("/[\p{Latin}]{2,}/ui", $string, $matches)) {
            $latin = $matches[0];
        }
        if (preg_match_all("/[\p{Hiragana}\p{Katakana}\p{Han}]/ui", $string, $matches)) {
            $cjk = $matches[0];
        }
        if (preg_match_all("/[\p{Hangul}]/ui", $string, $matches)) {
            $hangul = $matches[0];
        }
        if (preg_match_all("/[\p{Thai}]{1,2}/ui", $string, $matches)) {
            $thai = $matches[0];
        }
        if (preg_match_all("/[\p{Arabic}]{2,}/ui", $string, $matches)) {
            $arabic = $matches[0];
        }
        if (preg_match_all("/[\p{Cyrillic}]{2,}/ui", $string, $matches)) {
            $cyrillic = $matches[0];
        }
        if (preg_match_all("/[\p{Devanagari}]+/ui", $string, $matches)) {
            $devanagari = $matches[0];
        }

        return [...$latin, ...$cjk, ...$hangul, ...$thai, ...$arabic, ...$cyrillic, ...$devanagari];
    }

    public static function extractUniqueWords(string $string) {
        $words = self::extractWords($string);

        $unique_words = [];
        foreach ($words as $word) {
            if (strlen($word) < 3) {
                continue;
            }
            $word = preg_replace("/[^\p{L}]/u", "", $word);
            if (!$word) {
                continue;
            }
            $word = mb_strtolower($word);
            if (in_array($word, $unique_words)) {
                continue;
            }
            $unique_words[] = $word;
        }
        return $unique_words;
    }

    public static function isLatin(string $string) {
        $string = self::trim($string);
        if (mb_strlen($string) < 2) {
            return !preg_match( "/[\p{Hiragana}\p{Katakana}\p{Han}\p{Hangul}]/ui", $string);
        }
        return !preg_match( "/[\p{Hiragana}\p{Katakana}\p{Han}\p{Hangul}]\s?[\p{Hiragana}\p{Katakana}\p{Han}\p{Hangul}]/ui", $string);
    }

    public static function isJapanese(string $string)
    {
        //http://www.rikai.com/library/kanjitables/kanji_codes.unicode.shtml
        return preg_match( "/[\p{Hiragana}\p{Katakana}\p{Han}]\s?[\p{Hiragana}\p{Katakana}\p{Han}]/ui", $string);
    }

    public static function detectLanguage(string $string) {
        try {
            $client = new TikaHelper();
            return $client->getLanguage($string);
        } catch (\Exception|RequestException) {
            return null;
        }
    }

    public static function unicodeConvert(string $string)
    {
        $unicode = [
            'Ⅰ' => 'I',
            'Ⅱ' => 'II',
            'Ⅲ' => 'III',
            'Ⅳ' => 'IV',
            'Ⅴ' => 'V',
            'Ⅵ' => 'VI',
            'Ⅶ' => 'VII',
            'Ⅷ' => 'VIII',
            'Ⅸ' => 'IX',
            'Ⅹ' => 'X',
            "０" => '0',
            "１" => '1',
            "２" => '2',
            "３" => '3',
            "４" => '4',
            "５" => '5',
            "６" => '6',
            "７" => '7',
            "８" => '8',
            "９" => '9',
        ];

        foreach ($unicode as $key => $value) {
            $string = preg_replace("/$key/ui", $value, $string);
        }

        return $string;
    }

    public static function detectLanguageByTika(string $string) {
        $client = new \HocVT\TikaSimple\TikaSimpleClient();

        $language = $client->language($string);
        if ($language == 'zh-TW' || $language == 'zh-CN') {
            $language = 'zh';
        }
        if ($language == 'sv') {
            $language = 'se';
        }
        return $language;
    }

    public static function trim($str)
    {
        return preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $str);
    }

    protected static ?string $last_encoding = null;
    public static function vnEncodeFix(?string $string, bool $use_last_encoding = false) : ?string {

        if ($use_last_encoding) {
            $sourceEncode = Detector::usingCode($string);
            if (!$sourceEncode || $sourceEncode == Code::CHARSET_UNICODE) {
                $sourceEncode = self::$last_encoding;
            } else {
                self::$last_encoding = $sourceEncode;
            }
        } else {
            $sourceEncode = null;
        }

        try{
            $string = Converter::changeEncode($string, Code::CHARSET_UNICODE, $sourceEncode);
        }catch (EncodeException $ex){
//            dump("Warning encode : " . $ex->getMessage());
        }
        [$string] = self::fixVnSimpleError($string);
        return $string;
    }

    public static function fixVnSimpleError($string, $matched_before = '', $check_string = null) : array {
        $check_string = $check_string ?: $string;
        $matching = "";
        $matched = false;
        if(str_contains($matched_before, "Ƣ") || $matched = preg_match("/\wƢ[ơờớởu]/ui", $check_string)){
            $string = preg_replace("/Ƣ/u", "Ư", $string);
            $string = preg_replace("/ƣ/u", "ư", $string);
            $matching .= $matched ? "Ƣ" : "";
        }
        $matched = false;
        if(str_contains($matched_before, "−") || $matched = preg_match("/\w\−[ơờớởu]/ui", $check_string)){
            $string = preg_replace("/\−/ui", "ư", $string);
            $matching .= $matched ? "−" : "";
        }
        return [$string, $matching];
    }

    public static function resetLastEncode() : void {
        self::$last_encoding = null;
    }

    public static function encodeFix(?string $string): ?string {
        if (!config('converters.convert_encode')) {
            return $string;
        }

        return self::vnEncodeFix($string);
    }

    public static function extractEmails(string $content = '') {
        if(preg_match_all(
        //"/\s([a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6})*\s/",
            "/\s([a-z0-9_\.\+-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})(\s|$)/ui",
            $content,
            $matches)) {
            return array_map(fn($email) => self::trim($email), $matches[0]);
        }

        return [];
    }

    public static function extractPhones(string $content = '', bool $prefix = true) {
        if ($prefix) {
            $regex = "/(phone|tel|fax|Điện thoại|teléfonos|✆).{1,20}\+?\d{0,3}\s?(\(\d+\)|[\s\-\.]?\d{2,4})[\s\-\.]?\d{2,4}([\s\-\.]?\d{3,4})+/ui";
        } else {
            $regex = "/\+?\d{0,3}\s?(\(\d+\)|[\s\-\.]?\d{2,4})[\s\-\.]?\d{2,4}([\s\-\.]?\d{3,4})+/ui";
        }

        if (preg_match_all($regex, $content, $matches)) {
            $phones = array_map(fn($p) => self::trim($p), $matches[0]);
            return array_values(array_filter($phones, fn($p) => strlen($p) > 7));
        }

        return [];
    }

}
