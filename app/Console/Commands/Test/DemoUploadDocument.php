<?php

namespace App\Console\Commands\Test;

use App\Libs\MimeHelper;
use App\Models\Document;
use App\Service\CountPages;
use App\Service\MakePDF;
use App\Service\MakeText;
use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class DemoUploadDocument extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:upload_document';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
    public function handle(): int
    {
//        $user = User::first();

        $crawled_links = $this->getDocumentFromFiles();

        foreach ($crawled_links as $crawled_link) {
            if (empty($crawled_link['url'])) continue;
            $this->info("\tProcessing " . $crawled_link['url']);
            try {
//                $crawled_link['content'] = file_get_contents($crawled_link['url']);
                $upload_result = $this->uploadDemo($crawled_link);
                $this->warn("\tCreated success: " . $upload_result->id);

            } catch (\Exception $exception) {
                $this->error("\t" . $exception->getMessage());
            }
        }
        return self::SUCCESS;
    }

    protected function getDocumentFromFiles()
    {
        //        foreach ($crawled_links as $crawled_link) {
//            if (str_contains($crawled_link['url'], "://")) {
//                $crawled_link['referer'] = $crawled_link['url'];
//            }
//            yield $crawled_link;
//        }
        return include base_path('database/seeders/crawled_links.php');
    }

    /**
     * @throws \Exception
     */
    protected function uploadDemo($crawled_link)
    {
        // Tạo một instance của File từ đường dẫn tuyệt đối
        $file_upload = new File($crawled_link['url']);;
        $mimeType = $file_upload->getMimeType();
        $type = MimeHelper::getCode($mimeType);
        $disk = "public";
        $destination_path = 'public/pdftest';

        // Upload file vào thư mục lưu trữ
        $file_path = Storage::putFile($destination_path, $file_upload);
        $last_path = str_replace("public/", "", $file_path);
        $document = Document::create([
            'user_id' => 1,
            'title' => $crawled_link['title'],
            'category_id' => 1,
            'price' => 0,
            'type' => $type,
            'disks' => $disk,
            'source_url' => $last_path,
            'path' => $last_path,
            'language' => 'en',
            'country' => 'GB',
            'active' => true,
            'is_public' => true,
            'is_approved' => 1,
            'can_download' => true
        ]);
        $document = MakePDF::makePdf($document);
        // Format size
        $size = $file_upload->getSize();

        $formattedSize = $document->formatSizeUnits($size);

        $total_page = CountPages::TotalPages($document);

        // Get fulltext
        $full_text = MakeText::makeText($document);
        // Generate description
        $description = MakeText::makeDescription($full_text);
        $document->original_size = $size;
        $document->original_format = $formattedSize;
        $document->full_text = $full_text;
        $document->description = $description;
        $document->page_number = $total_page;
        $document->save();
        return $document;
    }

}
