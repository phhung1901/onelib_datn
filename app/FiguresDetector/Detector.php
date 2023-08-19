<?php

namespace App\FiguresDetector;

use App\FiguresDetector\Entities\FigureCollection;
use App\Libs\PdfToCairo;
use GuzzleHttp\Exception\GuzzleException;
use ThikDev\PdfParser\Converter\PdfToText;
use ThikDev\PdfParser\Exceptions\ParseException;
use ThikDev\PdfParser\Parser;

class Detector
{
    protected AI_Client $client;

    protected array $options = [];

    /**
     * @param string|null $host
     * @param string|null $token
     * @param array $options
     */
    public function __construct(string $host = null, string $token = null, array $options = []) {
        $this->client = new AI_Client($host, $token);
        $this->options = $options;
    }

    public function ping() {
        return $this->client->ping();
    }

    /**
     * @param mixed $pdf_resource
     * @param int $min_good_figures
     * @param int $min_good_score
     * @return FigureCollection
     * @throws GuzzleException
     * @throws ParseException
     */
    public function handle(mixed $pdf_resource, int $min_good_figures = 150, int $min_good_score = 0) {
        $result = new Entities\FigureCollection();
        $xml = (new PdfToText())->convertStream($pdf_resource,1,200, true);
        if(!$xml) {
            return $result;
        }
        $document_parser = $this->parser($xml);

        /** @var \ThikDev\PdfParser\Objects\Page $page */
        foreach ($document_parser->getPages() as $key => $page) {
            if ($min_good_figures <= 0) break;
            if ($page->is_table_content) continue;
            $lines = $page->getMainLines();

            /** @var \ThikDev\PdfParser\Objects\Line[] $captions */
            $captions = [];

            /** @var \ThikDev\PdfParser\Objects\Line $line */
            foreach ($lines as $i => $line) {
                if ($line->in_toc) {
                    $captions = [];
                    break;
                }
                if ($line->merge_up) continue;
                if (Utils::isCaptionOfFigure($line->text)) {
                    $captions[] = Utils::fullCaption($lines, $i);
                }
            }

            if (!empty($captions) && count($captions) < 7) {
                $image_content = (new PdfToCairo())->convertStream($pdf_resource, $key+1, $page->width);
                $figures = $this->client->detect($image_content);

                [$page_number, $page_width, $page_height] = [$key+1, $page->width, $page->height];
                $figures = $this->mergeFigureAndCaption($figures, $captions, $page_number, $page_width, $page_height);
                foreach ($figures as $figure) {
                    $figure = Utils::calculateScoreFigure($figure);
                    $figure = Utils::limitCaption($figure);
                    if ($figure->score > $min_good_score) $min_good_figures--;
                }
                $result = $result->merge($figures);
            }
        }

        return $result;
    }

    /**
     * @param string $pdf_path
     * @param int $last_page
     *
     * @return \ThikDev\PdfParser\Objects\Document
     * @throws \ThikDev\PdfParser\Exceptions\ParseException
     */
    protected function parser($xml, int $last_page = 200) {
        $parser = new Parser('', $last_page, 1, $xml, true);
//        $parser->addProcessAfter(\App\PdfParser\DetectNoiseContent::class);
        $parser->addProcessAfter(\ThikDev\PdfParser\Process\MergeDashEndedLines::class);
        return $parser->process();
    }

    /**
     * Merge Figure and Caption
     * @param Entities\FigureCollection $collection
     * @param array $captions
     * @param int $page_number
     * @param int $page_width
     * @param int $page_height
     * @return Entities\FigureCollection
     */
    protected function mergeFigureAndCaption(Entities\FigureCollection $collection, array $captions, int $page_number, int $page_width, int $page_height) {
        return $collection->map(function ($item, $key) use ($page_number, $page_width, $page_height, $captions) {
            /** @var Entities\Figure $item */
            $item->page = $page_number;
            $item->page_width = $page_width;
            $item->page_height = $page_height;

            if (isset($captions[$key])) {
                /** @var \ThikDev\PdfParser\Objects\Line[] $captions */
                $item->caption = $captions[$key]->text;
                $item->caption_position = [
                    "x-top" => $captions[$key]->left,
                    "y-top" => $captions[$key]->top,
                    "x-bottom" => $captions[$key]->left + $captions[$key]->width,
                    "y-bottom" => $captions[$key]->top + $captions[$key]->height,
                ];
            } else {
                $item->caption = '';
                $item->caption_position = [];
            }

            $item->caption_reliability = $item->reliability;

            return $item;
        });
    }
}
