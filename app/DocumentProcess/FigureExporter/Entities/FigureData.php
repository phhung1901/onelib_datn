<?php

namespace App\DocumentProcess\FigureExporter\Entities;


use Spatie\LaravelData\Data;

class FigureData extends Data
{
    public function __construct(
        public int $id = 0,
        public ?string $slug = '',
        public int $document_id = 0,
        public ?string $caption = '',
        public array $caption_info = [],
        public int $caption_reliability = 0,
        public int $figure_reliability = 0,
        public int $type = 0,
        public array $figure_position = [],
        public int $status = 0,
    )
    {
    }

    public function getCaptionInfo($info = 'caption') {
        if (empty($this->caption_info)) {
            $caption = preg_replace("/\p{Z}+/ui", " ", $this->caption);

            preg_match("/^(?<prefix>[^\s\W\p{N}]+\s+[^\p{Z}]+([.:\s\-)]{0,3}|$))(?<caption>.*)/ui", $caption, $matches);
            if ($matches) {
                $this->caption_info = [
                    'prefix' => $matches['prefix'],
                    'caption' => $matches['caption'],
                ];
            } else {
                $this->caption_info = [
                    'prefix' => '',
                    'caption' => $caption,
                ];
            }
        }

        return $this->caption_info[$info] ?? '';
    }
}
