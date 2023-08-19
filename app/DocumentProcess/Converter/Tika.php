<?php

namespace App\DocumentProcess\Converter;

use HocVT\TikaSimple\TikaSimpleClient;

class Tika implements ConverterInterface
{
    protected $options = [
        'host' => 'http://localhost',
        'port' => 9998,
        'timeout' => 30,
    ];

    protected TikaSimpleClient $client;


    public function __construct(?string $path, array $options = []) {
        $this->options = array_merge($this->options, $options);
        $this->client = new TikaSimpleClient(
            $this->options['host'] .
            ":" .
            $this->options['port']
        );
    }

    public function convert(mixed $input, int $first_page = 1, int $last_page = -1, string $input_format = null, string $output_format = null): string
    {
        $content = match ($output_format) {
            'html' => $this->client->rmeta($input, 'html', false),
            'txt', 'text' => $this->client->rmeta($input, 'text', false),
            default => throw new \Exception("Don not support output format $output_format"),
        };
        /** @var string $content */
        return $content;
    }
}