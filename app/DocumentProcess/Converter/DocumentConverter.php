<?php

namespace App\DocumentProcess\Converter;

class DocumentConverter
{
    protected array $drivers = [
        'qpdf' => Qpdf::class,
        'jodconverter' => JodConverter::class,
        'pdftotext' => PdfToText::class,
        'pdftohtml' => PdfToHtml::class,
        'pdftoxml' => PdfToXml::class,
        'tika' => Tika::class,
    ];

    protected array $driver_mapping = [];

    public function __construct()
    {
        $this->mapping();
    }

    protected function mapping()
    {
        foreach (config('converters.mapping') as $from => $others) {
            foreach ($others as $to => $driver) {
                $converter = $this->makeDriver($driver);
                $this->setMappingDriver($from, $to, $converter);
            }
        }
    }

    protected function makeDriver($driver)
    {
        if (!isset($this->drivers[$driver])) {
            $class = $driver;
        } else {
            $class = $this->drivers[$driver];
        }
        $path = config('converters.drivers.' . $driver . '.path');
        $options = config('converters.drivers.' . $driver . '.options');
        return new $class($path, $options);
    }

    protected function setMappingDriver(string $input, string $output, $converter)
    {
        if (isset($this->driver_mapping[$input])) {
            $this->driver_mapping[$input][$output] = $converter;
        } else {
            $this->driver_mapping[$input] = [
                $output => $converter
            ];
        }
    }

    protected function getMappingDriver(string $input_format, string $output_format): ConverterInterface
    {
        $driver = \Arr::get($this->driver_mapping, "$input_format.$output_format");
        if (!$driver) {
            throw new \Exception("No converter was assigned to convert from $input_format to $output_format");
        }

        return $driver;
    }

    public function convert(
        mixed $content,
        string $input_format = null, string $output_format = null,
        array $options = []
    ): string
    {
        $first_page = $options['first_page'] ?? 1;
        $last_page = $options['last_page'] ?? -1;

        $driver = $this->getMappingDriver($input_format, $output_format);
        return $driver->convert($content, $first_page, $last_page, $input_format, $output_format);
    }
}
