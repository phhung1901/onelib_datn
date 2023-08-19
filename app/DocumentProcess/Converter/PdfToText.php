<?php

namespace App\DocumentProcess\Converter;

use App\DocumentProcess\Utilities\TemporaryDirectory;
use Symfony\Component\Process\Process;

class PdfToText implements ConverterInterface
{
    public string $bin = "pdftotext";
    public int $timeout = 69;// 69 seconds

    private TemporaryDirectory $tmp;

    protected Process $process;
    protected array $command = [];

    public function __construct(?string $path, array $options = []) {
        if ($path) {
            $this->bin = $path;
        }

        if (!empty($options['timeout'])) {
            $this->timeout = $options['timeout'];
        }

        $this->tmp = new TemporaryDirectory(config('converters.tmp.root'));
        $this->tmp->autoDestroyed(false);
    }

    public function convert(mixed $input, int $first_page = 1, int $last_page = -1, string $input_format = null, string $output_format = null): string
    {
        $tmp_path = $this->tmp->tmpPath('url');
        try {
            file_put_contents($tmp_path, $input);

            $command = [$this->bin,
                "-f",
                $first_page,
                "-l",
                $last_page,
                $tmp_path,
                "-",
            ];

            $this->process = new Process($command);
            $this->process->setTimeout($this->timeout);
            $this->process->run();

            return $this->output();
        } finally {
            @unlink($tmp_path);
        }
    }

    protected function output()
    {
        return $this->process->getOutput();
    }
}