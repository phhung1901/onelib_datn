<?php

namespace App\Console\Commands\Tasks;

use App\Models\Document;
use App\Models\Tag;
use App\Service\MakePDF;
use App\Service\MakeText;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Mime\MimeTypes;

class GetDocumentFromOnelib extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:get_document
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
    protected $description = 'Command get document from onelib';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $homepage = "https://data01.123dok.com/api/documents/search?";
    protected $page = 1;
    protected $disks = "public";

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
        $mimeTypes = new MimeTypes();
        $client = new Client();

        while (true){
            sleep($sleep);
            $url = $this->getPages($this->page);
            $this->info($url);
            // Tạo yêu cầu GET tới API với Bearer Token
            $response_api = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
            ]);

            // Lấy dữ liệu từ phản hồi
            $response = json_decode($response_api->getBody(), true);

            $datas = $response['data'];

            foreach ($datas as $data) {
                try {
                    if ($existed = Document::where('title', $data['title'])->first()){
                        $this->warn('Duplicate with id: '.$existed->id);
                        continue;
                    }
                    $sourceUrl = $data['original_file'];

                    $response_document = $client->get($sourceUrl);
                    $fileContent = $response_document->getBody();
                    $destinationPath =  'public/pdftest'; // Đường dẫn đích để lưu tệp (được tạo trong thư mục "storage")

                    $extension = $mimeTypes->getExtensions($response_document->getHeaderLine('Content-Type'));

                    if (!empty($extension)) {
                        $extension = $extension[0];
                        $fileName = 'file_' . Str::random(15) . '.' . $extension;
                        Storage::put($destinationPath . '/' . $fileName, $fileContent);
                        $last_path = 'pdftest/'.$fileName;
                        $document = Document::create([
                            'user_id' => 1,
                            'title' => $data['title'],
                            'description' => $data['description'],
                            'category_id' => random_int(1,37),
                            'price' => 0,
                            'type' => $extension,
                            'disks' => $this->disks,
                            'source_url' => $last_path,
                            'path' => $last_path,
                            'page_number' => $data['pages'],
                            'language' => $data['language'],
                            'country' => 'GB',
                            'active' => true,
                            'is_public' => true,
                            'is_approved' => 1,
                            'can_download' => true
                        ]);
                        $document = MakePDF::makePdf($document);
                        // Format size
                        $size = $data['size'];
                        $formattedSize = $document->formatSizeUnits($size);

                        // Get fulltext
                        $full_text = MakeText::makeText($document);
                        // Generate description
                        $document->original_size = $size;
                        $document->original_format = $formattedSize;
                        $document->full_text = $full_text;
                        $document->save();
                        $tags = Tag::all();
                        if ($tags){
                            $randomTags = $tags->random(mt_rand(3, 10));
                            foreach ($randomTags as $randomTag) {
                                \DB::table('document_tag')->insert([
                                    'document_id' => $document->id,
                                    'tag_id' => $randomTag->id,
                                ]);
                            }
                        }
                        $this->info('Successful data collection: '.$document->id);
                    } else {

                        continue;
                    }

                }catch (\Exception $err){
                    $this->error('Error: '.$err->getMessage());
                    continue;
                }

            }
            $this->page++;
        }

        return self::SUCCESS;
    }

    protected function getPages($page): string
    {
        $url = 'page='.$page;
        return $this->homepage . $url;
    }
}
