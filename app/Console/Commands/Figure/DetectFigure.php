<?php

namespace App\Console\Commands\Figure;

use App\FiguresDetector\Detector as FigureDetector;
use App\Models\Document;
use Illuminate\Console\Command;

class DetectFigure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'detect:figure
    {--id= : id, ids, id range}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command detect image/table in document, use meta2 AI';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $document = Document::whereDate('created_at', '<=', today()->subDays(7))->sum('viewed_count');
        dd($document);
//        $id = $this->option('id');
//        $documents = Document::idRange($id)->get();
        $this->testFigure();
        return self::SUCCESS;
    }

    protected function testFigure() {
        $url = "http://lib.uet.vnu.edu.vn/bitstream/123456789/944/2/TomTat_LVThs_DinhChungDung.pdf";
        $service = new FigureDetector();
        $figures = $service->handle($this->download($url));
        dump($figures);
    }

    protected function download($url): string
    {
        $opts = [
            'http' => [
                'method' => "GET",
                'header' => "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:64.0) Gecko/20100101 Firefox/64.0\r\n",
            ],
            "ssl"  => [
                "verify_peer"      => false,
                "verify_peer_name" => false,
            ]
        ];
        $context = stream_context_create($opts);
        return file_get_contents($url, false, $context);
    }
}
