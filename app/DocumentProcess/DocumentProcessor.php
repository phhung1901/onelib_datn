<?php

namespace App\DocumentProcess;

use App\DocumentProcess\Utilities\ContentExporter;
use HocVT\TikaSimple\TikaSimpleClient;
use ThikDev\PdfParser\Objects\Document;

class DocumentProcessor
{

    protected Document $document;
    protected ?string $language;

    protected int $detect_toc_type;
    protected array $tableOfContentPages = [];

    protected $stopwords = [];
    protected ?TikaSimpleClient $tika = null;

    public function __construct(Document $document, array $config = [])
    {
        $this->document = $document;
        $this->language = $config['language'] ?? '';

        $this->init($config);
    }

    public function init($config) {
        // Tika
        if (!$this->tika) {
            $host = $config['tika']
                ?? config('doc_services.tika.host') . ":" . config('doc_services.tika.port');
            $this->tika = new TikaSimpleClient($host);
        }
        // Language
//        if (!$this->language) {
//            $sample_sentences = ContentExporter::getSampleSentences($this->document);
//            $this->language = $this->tika->language(implode(". ", $sample_sentences));
//        }

        // Vocabulary
    }

//    public function getToc(): Toc
//    {
//        if (empty($this->toc)) {
//            $toc_builder = new TocBuilder($this->document);
//            $toc_builder->detectToc();
//            $this->tableOfContentPages = $toc_builder->getTableOfContentPages();
//            $this->toc = $toc_builder->getToc();
//            $this->detect_toc_type = $toc_builder->getTypeDetect();
//        }
//
//        return $this->toc;
//    }

//    public function getDetectTocType(): int
//    {
//        return $this->detect_toc_type;
//    }

//    public function makeParts(): array
//    {
//        $parts = $this->generatePartsFromToc($this->getToc()->getChildrenArray());
//        return array_map(function ($item) {
//            $item['heading_quality'] = HeadingHelper::calculateHeadingQuality($item['heading']);
//            $item['ancestors'] = array_values(array_filter($item['ancestors'], function ($item) {
//                return HeadingHelper::calculateHeadingQuality($item['heading']) >= 100;
//            }));
//
//            return $item;
//        }, $parts);
//    }

    public function makeDescription() {
        //..
        return "[r]";
    }

    public function makeFulltext() {
        return ContentExporter::exportHtml($this->document);
    }

//    public function makeSummarize() {
//        $summarizer = new Summarizer(
//            $this->document,
//            $this->getToc(),
//        );
//        return $summarizer->makeSummarize();
//    }

//    private function generatePartsFromToc(array $toc_content, array $ancestors = []) {
//        $parts = [];
//
//        foreach ($toc_content as $part) {
//            $total_page = !empty($part['end_page']) ? $part['end_page'] - $part['start_page'] : 100;
//
//            if ($total_page > 5 && !empty($part['children'])) {
//                $children = $this->generatePartsFromToc(
//                    $part['children'],
//                    [...$ancestors, [
//                        'prefix' => $part['prefix'],
//                        'heading' => $part['heading'],
//                    ],]
//                );
//                if (empty($children)) {
//                    unset($part['children']);
//                    $part['ancestors'] = $ancestors;
//                    $parts[] = $part;
//                } else {
//                    array_push($parts, ...$children);
//                }
//            } elseif ($total_page > 2 && $part['start_page'] !== 0) {
//                unset($part['children']);
//                $part['ancestors'] = $ancestors;
//                $parts[] = $part;
//            }
//        }
//        return $parts;
//    }
}
