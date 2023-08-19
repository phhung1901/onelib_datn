<?php

namespace App\Console\Commands\Tasks;

use App\Models\Document;
use App\Models\Enums\TaskStatus;
use App\Models\Tag;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class MergeDocumentTag extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:merge_document_tag
    {--id= : id, ids, id range}
    {--force : Force}
    }
    ';

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
    public function handle()
    {
        $id = $this->option('id');
        $force = $this->option('force');

        $tags = Tag::all();
        Document::idRange($id)
            ->when(!$force, function (Builder $query) {
                $query->with('tags', function ($query) {
                    $query->where('tag_id', '!=', null);
                });
            })->chunkById(10, function ($documents) use ($tags) {
                if ($tags) {
                    foreach ($documents as $document) {
                        try {
                            $randomTags = $tags->random(mt_rand(3, 10));
                            foreach ($randomTags as $randomTag) {
                                \DB::table('document_tag')->insert([
                                    'document_id' => $document->id,
                                    'tag_id' => $randomTag->id,
                                ]);
                            }
                            $this->info("==> Result: Math success document [{$document->id}] with {$randomTags->count()} tags");
                        } catch (\Exception $err) {
                            $this->warn("Error: " . $err->getMessage());
                            continue;
                        }
                    }
                } else {
                    $this->warn("Error: No have tag to match");
                    return self::FAILURE;
                }
            });

        return self::SUCCESS;
    }
}
