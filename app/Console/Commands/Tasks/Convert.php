<?php

namespace App\Console\Commands\Tasks;

use App\DocumentProcess\Builder;
use App\DocumentProcess\Converter\DocumentConverter;
use App\Libs\MakePath;
use App\Libs\Nlp\Description\TextRankGenerator;
use App\Models\Document;
use App\Models\Enums\TypeDocument;
use Illuminate\Console\Command;

/**
 * @property DocumentConverter $convertor
 */
class Convert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:convert
    {--id= : id, ids, id range}
    {--f|force : Force to reconvert}

    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get fulltext document';

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
        $force = $this->option('force');
        $id = $this->option('id');
        $this->convertor = new DocumentConverter();

        if ($id){
            $documents = Document::idRange($id)->get();
        }
        else {
            if ($force) {
                $documents = Document::all();
            } else {
                $documents = Document::where('full_text', null)->get();
            }
        }
        foreach ($documents as $document) {
            try {

                if ($document->type != TypeDocument::PDF){
                    $this->makePdf($document);
                }
                $this->makeText($document);

                $this->info("Get fulltext of {$document->id} success");
            } catch (\Exception $err) {
                continue;
            }
        }
        return self::SUCCESS;
    }


    protected function makePdf(Document $document)
    {
        $path = $document->source_url;
        $original_file = \Storage::disk('public')->get($path);

        if ($document->source_url) {
            $pdf_content = $this->convertor->convert(
                content: $original_file,
                input_format: $document->type,
                output_format: 'pdf',
            );
        } else {
            $pdf_content = $original_file;
        }
        $path = 'pdf_maked/' . MakePath::make($document->id, '') . ".pdf.pdf";
        $saved = \Storage::disk('public')->put($path, $pdf_content);

        if ($saved) {
            $document->update([
                'path' => $path
            ]);
        } else {
            $this->error("Can not save file");
        }
    }

    protected function makeText(Document $document)
    {
//        if ($document->full_text && $document->description) {
//            return;
//        }
        try {
            $fulltext = $this->makeTextV2($document);
        } catch (\Exception $exception) {
            $this->error("\tConvert fulltext error : " . $exception->getMessage());
        }

        /** Create Description */
        $generator = TextRankGenerator::fromDSFullText($fulltext);
        $description = $generator->getDescription();
        $description = mb_substr($description, 0, 186) . "[r]";

        /** Save fulltext to $document->fulltext */
        $document->full_text = $fulltext;
        $document->description = $description;
        $document->save();
    }

    /**
     * @throws \Exception
     */
    protected function makeTextV2(Document $document)
    {
        $document_process = Builder::fromDocument($document)->get();
        return $document_process->makeFulltext();
    }
}
