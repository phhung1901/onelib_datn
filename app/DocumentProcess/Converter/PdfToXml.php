<?php

namespace App\DocumentProcess\Converter;

use Symfony\Component\Process\InputStream;
use Symfony\Component\Process\Process;

class PdfToXml implements ConverterInterface
{
    public string $bin = "pdftohtml";
    public int $timeout = 69;// 69 seconds
    protected Process $process;
    protected array $command = [];

    public function __construct(?string $path, array $options = []) {
        if ($path) {
            $this->bin = $path;
        }

        if (!empty($options['timeout'])) {
            $this->timeout = $options['timeout'];
        }
    }

    public function convert(mixed $input, int $first_page = 1, int $last_page = -1, string $input_format = null, string $output_format = null): string
    {
        $command = [$this->bin,
            "-i",
            "-xml",
            "-hidden",
            "-f",
            $first_page,
            "-l",
            $last_page,
            "-q",
            "-nodrm",
            "-stdout",
            "-",
            "nonsense",
        ];

        $_input = new InputStream();
        $this->process = new Process($command);
        $this->process->setTimeout($this->timeout);
        $this->process->setInput($_input);
        $this->process->start();
        if (is_string($input)) {
            $_input->write($input);
            $_input->close();
        } elseif (is_resource($input)) {
            while (!feof($input)) {
                $_input->write(fread($input, 8192));
            }
            $_input->close();
        }
        $this->process->wait();
        return $this->output();
    }

    protected function output()
    {
        return $this->process->getOutput();
    }
}