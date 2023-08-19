<?php

namespace App\Console\Commands\Tasks;

use App\Models\Tag;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class GetTagFromOnelib extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:get_tag
        {--page=1 : page}
    {--per_page= : limit per page}
    {--token= : Token}
    {--sleep=5 : sleep}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command get tags from onelib';
    protected $homepage = "https://data01.123dok.com/api/tags/suggest?";
    protected $page = 1;
    protected $disks = "public";

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
        $page = $this->option('page');
        $per_page = $this->option('per_page');
        $sleep = $this->option('sleep');
        $token = $this->option('token');
        $this->page = $page;
        $client = new Client();

        while (true) {
            sleep($sleep);
            $url = $this->getPages($this->page);
            $this->info($url);
            // Tạo yêu cầu GET tới API với Bearer Token
            $response_api = $client->request('GET', $url, [
                'headers' => [
//                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
            ]);

            // Lấy dữ liệu từ phản hồi
            $response = json_decode($response_api->getBody(), true);

            $datas = $response['data'];

            foreach ($datas as $data) {
                try {
                    if ($existed = Tag::where('name', $data['name'])->first()) {
                        $this->warn('Duplicate with id: ' . $existed->id);
                        continue;
                    }
                    $tag = Tag::firstOrCreate([
                        'normalized' => $data['normalized'],
                    ], [
                        'name' => $data['name']
                    ]);
                    $this->info('Successful data collection: ' . $tag->id);

                } catch (\Exception $err) {
                    $this->error('Error: ' . $err->getMessage());
                    continue;
                }

            }
            $this->page++;
        }

        return self::SUCCESS;
    }

    protected function getPages($page): string
    {
        $url = 'page=' . $page;
        return $this->homepage . $url;
    }
}
