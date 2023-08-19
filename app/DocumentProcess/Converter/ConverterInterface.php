<?php

namespace App\DocumentProcess\Converter;

interface ConverterInterface
{
    public function convert(mixed $input, int $first_page = 1, int $last_page = -1, string $input_format = null, string $output_format = null): string;
}