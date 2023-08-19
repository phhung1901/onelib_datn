<?php

namespace App\DocumentProcess\Converter;

use App\DocumentProcess\Utilities\TemporaryDirectory;
use Symfony\Component\Process\Process;

class Qpdf implements ConverterInterface
{
    public string $bin = 'qpdf';
    private TemporaryDirectory $tmp;

    protected Process $process;
    protected array $command;

    public function __construct(?string $path, array $options = []) {
        if ($path) {
            $this->bin = $path;
        }

        $this->tmp = new TemporaryDirectory(config('converters.tmp.root'));
        $this->tmp->autoDestroyed(false);
    }

    /**
     * qpdf --linearize --collate --empty --min-version=1.5 --pages qpdf.pdf 1-10 -- qpdf.o.pdf
     * @param string|null $output_format
     */
    public function convert(mixed $input, int $first_page = 1, int $last_page = -1, string $input_format = null, string $output_format = null): string
    {
        $tmp_path = $this->tmp->tmpPath('url');
        try {
            file_put_contents($tmp_path, $input);

            $command = [$this->bin,
                '--linearize',
                '--min-version=1.5',
                '--object-streams=generate',
            ];

            if ($first_page > 1 || $last_page > 0) {
                $command = array_merge($command, [
                    '--collate',
                    '--empty',
                    '--pages',
                    $tmp_path,
                    $first_page . "-" . $last_page,
                    '--'
                ]);
            } else {
                $command[] = $tmp_path;
            }
            $command[] = "-";

            $this->process = new Process($command);
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