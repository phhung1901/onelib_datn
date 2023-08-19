<?php

namespace App\DocumentProcess;

use App\Models\Document;
use ThikDev\PdfParser\Converter\PdfToText;
use ThikDev\PdfParser\Parser;

class Builder
{

    protected Parser $parser;
    protected array $config = [];

    public function __construct(string $xml, $last_page = -1, $first_page = 1)
    {
        $this->parser = new Parser('', $last_page, $first_page, $xml);
    }

    public static function fromContents($file_contents, $last_page = 200, $first_page = 1, $output_hidden_text = true)
    {
        $xml = (new PdfToText())->convertStream($file_contents, $first_page, $last_page, $output_hidden_text);
        return new self($xml);
    }

    public static function fromDocument(Document|int $document, $last_page = 200, $first_page = 1, $output_hidden_text = true)
    {
        if (!($document instanceof Document)) {
            $document = Document::findOrFail($document);
        }
        if ($document->path) {
            $path = $document->path;
            $content = \Storage::disk('public')->get($path);
        } else {
            throw new \Exception('Document does not have pdf file ', $document->id);
        }
        return self::fromContents($content, $last_page, $first_page, $output_hidden_text);
    }

    public function addProcessAfter($process, ...$after_processes)
    {
        $this->parser->addProcessAfter($process, ...$after_processes);
        return $this;
    }

    public function addProcessBefore($process, ...$before_processes)
    {
        $this->parser->addProcessBefore($process, ...$before_processes);
        return $this;
    }

    public function replaceProcess($search, $replacement)
    {
        $this->parser->replaceProcess($search, $replacement);
        return $this;
    }

    public function setLanguage($language)
    {
        $this->config['language'] = $language;
        return $this;
    }

    public function setTika($host)
    {
        $this->config['tika'] = $host;
        return $this;
    }

    public function getPipeline()
    {
        return $this->parser->getPipeline();
    }

    public function getXml()
    {
        return $this->parser->getXml();
    }

    public function getDocumentParser()
    {
        return $this->parser->process();
    }

    public function get(): DocumentProcessor
    {
        $document = $this->getDocumentParser();
        return new DocumentProcessor($document, $this->config);
    }
}
