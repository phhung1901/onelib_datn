<?php

namespace App\DocumentProcess\Utilities;

use ThikDev\PdfParser\Objects\Component;
use ThikDev\PdfParser\Objects\Document;
use ThikDev\PdfParser\Objects\Page;

class LinksExtractor
{
    public static function exportLinks(Document $document) {
        $links = [];
        /** @var Page $page */
        foreach ($document->getPages() as $p => $page) {
            /** @var Component $component */
            foreach ($page->components as $k => $component) {
                if (mb_strpos($component->raw, "<a ") !== false) {
                    $links = array_merge($links, static::parse($component->raw, $p, $k));
                }
            }
        }
        $links = array_filter($links);
        return static::reduceLinks($links);
    }

    private static function parse($raw, $page, $id) {
        $result = [];
        if(preg_match_all( "/<a [^<>]*href=[\"\']([^<>\"\']*)[\"\'][^<>]*>([^<>]*)<\/a>/ui", $raw, $matches)){
            for ($i = 0; $i < count($matches[0]); $i++){
                $result[] = [
                    $matches[1][$i], // url
                    $matches[2][$i], // anchor text
                    $page, // page id
                    $id, // component id
                ];
            }
        }
        return $result;
    }

    private static function reduceLinks($links) {
        // Reduce lần 1 : ghép anchor text
        $last = null;
        $result = [];
        foreach ($links as $link) {
            if (!str_contains($link[0], "//") // bỏ qua link ko phải url
                || str_contains($link[0], "file://") // bỏ qua link đến local file
            ) {
                if ($last) {
                    $result[] = $last;
                }
                $last = null;
                continue;
            }
            if ($last) {
                if ($link[0] == $last[0]
                    && $link[2] == $last[2]
                    && $link[3] - $last[3] < 2) { // link phát hiện liên tiếp nhau trong 1 trang mới merge
                    $last[1] .= $link[1];
                    $result[count($result) - 1] = $last;
                } else {
                    $last = $link;
                    $result[] = $last;
                }
            } else {
                $result[] = $link;
                $last = $link;
            }
        }
        // reduce lần 2 : loại bỏ link trùng
        $last_result = [];
        foreach ($result as $link) {
            if (isset($last_result[$link[0]])) {
                if (mb_strlen($last_result[$link[0]]) < $link[1]) { // lấy anchor text dài hơn
                    $last_result[$link[0]] = $link[1];
                }
            } else {
                $last_result[$link[0]] = $link[1];
            }
        }

        return $last_result;
    }
}