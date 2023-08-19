<?php

namespace App\DocumentProcess\Utilities;

use App\DocumentProcess\TocBuilder\Bookmark;
use App\Libs\RewriteSentenceClient;
use App\Libs\StringUtils;
use ThikDev\PdfParser\Objects\Document;
use ThikDev\PdfParser\Objects\Document as DocumentParser;
use ThikDev\PdfParser\Objects\Line;
use ThikDev\PdfParser\Objects\Page;
use ThikDev\PdfParser\Objects\Text;

class ContentExporter
{
    public static function exportHtml(Document $document) {
        $text = "";
        /** @var \ThikDev\PdfParser\Objects\Page $page */
        foreach ($document->getPages() as $page_index => $page) {
            if($page_index > 200) break;

            $page_html = '<span class=\'text_page_counter\'>(' . ($page_index + 1) . ')</span>';
            $page_html .= '<div class=\'page_container\' data-page=' . ($page_index + 1) . '>';

            if (config('converters.convert_encode')){
                $page_html .= StringUtils::vnEncodeFix($page->getHtml());
            }else{
                $page_html .= $page->getHtml();
            }

            $page_html .= '</div>';
            $page_html .= '<br>';
            $text .= $page_html;
        }

        return $text;
    }

    public static function clipHTML(
        DocumentParser $document_parser,
        Bookmark       $start_position,
        Bookmark       $end_position,
        int            $max_words = -1,
    ) {
        $start_page = $start_position->page;
        $end_page = $end_position->page ?: $document_parser->pageCount();
        if ($start_page == 0) {
            return ['html' => "", 'words_count' => 0, 'text_quality' => 0];
        }

        $html = "";
        $words_count = 0;
        $_text_quality = [];

        foreach ($document_parser->getPages() as $page_index => $page) {
            $total_lines_pdf = 0;
            $total_lines_html = 0;

            if ($page->is_table_content) continue;
            if ($page_index < $start_page - 1) continue;
            if ($page_index > $end_page - 1) break;

            if ($page_index == $start_page - 1) {
                $page = self::removeTopFromText($page, $start_position->content);
            }
            if ($page_index == $end_page - 1) {
                $page = self::removeBottomFromText($page, $end_position->content);
            }

            $html .= $page->getHtml();
            $words_count += StringUtils::wordsCount($page->getText());

            $bottoms = [];
            array_map(function ($object) use (&$bottoms, &$total_lines_html) {
                $bottoms[] = $object->bottom();
                if($object instanceof Text || $object instanceof Line) {
                    if (!$object->merge_up) $total_lines_html++;
                }
            }, $page->objects);
            $total_lines_pdf += count(array_unique($bottoms));
            if ($total_lines_pdf) {
                $_text_quality[] = $total_lines_html/($total_lines_pdf*1.2);
            }

            if ($max_words != -1 && $words_count > $max_words) break;
        }

        if (count($_text_quality)) {
            $text_quality = array_sum($_text_quality)/count($_text_quality);
        } else {
            $text_quality = 0;
        }
        $text_quality = min(100 - (int) round($text_quality * 100) + 45, 100);

        return compact('words_count', 'text_quality', 'html');
    }

    public static function removeTopFromText(Page $page, $string) {
        $key = self::getLineIndex($page, $string);
        if ($key !== null)
            $page->objects = array_splice($page->objects, $key+1);
        return $page;
    }

    public static function removeBottomFromText(Page $page, $string) {
        $key = self::getLineIndex($page, $string);
        if ($key !== null)
            $page->objects = array_splice($page->objects, 0, $key);
        return $page;
    }

    public static function getLineIndex(Page $page, $string) {
        $string = StringUtils::normalize($string);
        foreach ($page->objects as $key => $object) {
            if($object instanceof Text || $object instanceof Line) {
                $line_normalize = str_replace(' ', '', StringUtils::normalize($object->text));
                $max_length = max(mb_strlen($line_normalize), 5);

                $pattern = \Str::limit(str_replace(' ', '', $string), $max_length, '');
                if (preg_match("/^".preg_quote($pattern, '/')."/ui", $line_normalize))
                    return $key;
            }
        }
        return null;
    }

    public static function getParagraphs(Page $page){
        $paragraphs = [];
        $text = null;
        foreach ($page->objects as $k => $object){
//            if(!($object instanceof Line)){
//                continue;
//            }
            if($page->inFooter( $object ) || $page->inHeader( $object )){
                continue;
            }
            if(!$text){
                $text = $object->text;
            }elseif($object->merge_up){
                $text .= $object->text;
            }else{
                $paragraphs[] = $text;
                $text = $object->text;
            }
        }
        if($text){
            $paragraphs[] = $text;
        }
        return $paragraphs;
    }

    public static function getSampleSentences(Document $document, $limit = 1, $min_words = 5, $min_length = 20, $max_words = 0, $max_length = 0) : array {
        $sentences = [];
        foreach ($document->getPages() as $page) {
            /** @var Line $line */
            foreach (self::getParagraphs($page) as $paragraph) {
                $words_count = StringUtils::wordsCount($paragraph);
                $str_len = mb_strlen($paragraph);
                if (
                    $words_count > $min_words
                    && $str_len > $min_length
                ) {
                    $sentences[] = $paragraph;
                }

                if (count($sentences) >= $limit)
                    break;
            }
        }
        return $sentences;
    }

    public static function contentRestructuring($content) {
        $content = htmlentities($content);
        if (preg_match("/\014/ui", $content)) {
            $count = 1;
            $content = "<span class='text_page_counter'>(1)</span>" . preg_replace_callback("/\014/ui", function ($matches) use (&$count) {
                    $count++;
                    return "<br/><br/><span class='text_page_counter'>(" . $count . ")</span> ";
                }, $content);
        }
        $content = preg_replace("/([\s\t]?\n){2,}/", ". ", $content);
        return preg_replace("/[\n\t\r\s]+/ui", " ", $content);
    }

    /**
     * Support Outline (new fulltext)
     * @param array $parts
     * @return string
     */
    public static function exportOutlineHtml(array $parts, array $figures = []) {
        $html = "";

        foreach ($parts as $part) {

            if ($part['heading'] && HeadingHelper::calculateHeadingQuality($part['heading']) >= 100) {
                if (!empty($part['important_sentences']) || $part['children']) {
                    $html .= "<h2>{$part['heading']}</h2>";
                } else {
                    $html .= "<p>{$part['heading']}</p>";
                }
            }

            if (empty($part['important_sentences']) && $part['children']) {
                foreach ($part['children'] as $children) {
                    if ($children['heading'] && HeadingHelper::calculateHeadingQuality($children['heading']) >= 100) {
                        if ($children['important_sentences']) {
                            $html .= "<h3>{$children['heading']}</h3>";
                        } else {
                            $html .= "<p>{$children['heading']}</p>";
                        }
                    }

                    $html .= self::getHeadingTree($children['children']);

                    foreach (array_chunk($children['important_sentences'] ?? [], 3) as $sentences) {
                        $_text = implode(" ", array_map(fn($s) => trim($s, ". \t\n\r\0\x0B") . ".", $sentences));
                        $html .= "<p>".self::rewrite_sentence($_text)."</p>";
                    }

                    $figure = \Arr::first(
                        \Arr::where($figures, fn(FigureData $_f) => ($_f->figure_position['p'] > $children['start_page'] && $_f->figure_position['p'] < $children['end_page']))
                    );
                    $html .= $figure ? "<!-- Figure: " . json_encode($figure) . " End Figure -->" : "";

                }
            } else {
                $html .= self::getHeadingTree($part['children']);
                foreach (array_chunk($part['important_sentences'] ?? [], 3) as $sentences) {
                    $_text = implode(" ", array_map(fn($s) => trim($s, ". \t\n\r\0\x0B") . ".", $sentences));
                    $html .= "<p>".self::rewrite_sentence($_text)."</p>";
                }

                $figure = \Arr::first(
                    \Arr::where($figures, fn(FigureData $_f) => ($_f->figure_position['p'] > $part['start_page'] && $_f->figure_position['p'] < $part['end_page']))
                );
                $html .= $figure ? "<!-- Figure: " . json_encode($figure) . " End Figure -->" : "";
            }
        }

        if (!preg_match("/<h2>.*?<\/h2>/", $html)) {
            $html = preg_replace("/<h3>(.*?)<\/h3>/", "<h2>$1</h2>", $html);
        }

        return "<div class='page_container'>$html</div>";
    }

    protected static function getHeadingTree(array $array) {
        $html = "";
        if (count($array)) {
            $html .= "<ul>";
            foreach ($array as $item) {
                $html .= "<li><i>{$item['heading']}</i></li>";
                if (count($item['children'])) {
                    $html .= "<ul>";
                    foreach ($item['children'] as $_item) {
                        $html .= "<li><i>{$_item['heading']}</i></li>";
                    }
                    $html .= "</ul>";
                }
            }
            $html .= "</ul>";
        }

        return $html;
    }

    protected static function rewrite_sentence(string $text) {
        try {
            $result = retry(5, function () use ($text) {
                return (new RewriteSentenceClient())->rewrite_text($text);
            });
            return $result['new_text'];
        } catch (\Exception $exception) {
            \Log::error('Rewrite system error: ' . $exception->getMessage());
            return $text;
        }
    }
}
