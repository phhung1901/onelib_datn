<?php

namespace App\DocumentProcess\Converter;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Utils;

class JodConverter implements ConverterInterface
{
    protected Client $client;

    protected $options = [
        'base_uri' => 'http://localhost:8081',
        'timeout' => 300,
        'verify' => false,
    ];

    public function __construct(?string $path, array $options = [])
    {
        if($path){
            $this->options['base_uri'] = $path;
        }
        $this->options = array_merge($this->options, $options);
        $this->client = new Client($this->options);
    }

    public function convert(mixed $input, $first_page = 1, $last_page = -1, string $input_format = null, string $output_format = null): string
    {
        $response = $this->client->post('/lool/convert-to/pdf', [
            'multipart' => [
                [
                    'name' => 'data',
                    'contents' => Utils::streamFor($input),
                    'filename' => "document." . $input_format,
                ],
            ],
        ]);

        return $response->getBody()->getContents();
    }
}