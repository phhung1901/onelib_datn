<?php

namespace App\Models;

use App\DocumentProcess\Builder;
use App\Libs\Nlp\Description\TextRankGenerator;
use App\Libs\SeoSlugGenerator;
use App\Models\Traits\HasIdRangeScope;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class Document extends Model
{
    use CrudTrait;
    use HasIdRangeScope;

    protected $table = 'documents';
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'source_url',
        'description',
        'page_number',
        'price',
        'original_size',
        'original_format',
        'full_text',
        'disks',
        'path',
        'type',
        'language',
        'country',
        'rating_value',
        'rating_count',
        'viewed_count',
        'downloaded_count',
        'shared_count',
        'active',
        'is_public',
        'is_approved',
        'can_download',
        'approved_at',
        'created_at',
        'updated_at',
    ];

    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if ($model->price === null) {
                $model->price = 0;
            }
            if (!$model->user_id){
            $model->user_id = backpack_user()->id;
            }
            $model->slug = Str::slug((new SeoSlugGenerator($model->title))->run());


//            $document_process = Builder::fromDocument($model)->get();
//            $fulltext = $document_process->makeFulltext();
//            $model->full_text = $fulltext;
//            $generator = TextRankGenerator::fromDSFullText($fulltext);
//            $description = $generator->getDescription();
//            $description = mb_substr($description, 0, 186) . "[r]";
//            $model->description = $description;
        });

        static::updating(function ($model) {
            $model->slug = Str::slug((new SeoSlugGenerator($model->title))->run());
//            if ($model->getAttribute('source_url')) {
//                $oldImage = $model->getOriginal('source_url');
//                $oldImage = str_replace('storage/', '', $oldImage);
//                $newImage = $model->getAttribute('source_url');
//                if ($oldImage !== $newImage) {
//                    \Storage::disk('public')->delete($oldImage);
//                }
//            }

//            $document_process = Builder::fromDocument($model)->get();
//            $fulltext = $document_process->makeFulltext();
//            $model->full_text = $fulltext;
//            $generator = TextRankGenerator::fromDSFullText($fulltext);
//            $description = $generator->getDescription();
//            $description = mb_substr($description, 0, 186) . "[r]";
//            $model->description = $description;
        });

        static::deleting(function ($model) {
            $file = $model->path;
            if ($file) {
                \Storage::disk('public')->delete($file);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'document_tag', 'document_id', 'tag_id');
    }

    public function user_bookmark(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_document', 'document_id', 'user_id');
    }

    public function payments(): BelongsToMany
    {
        return $this->belongsToMany(Payment::class, 'payment_documents', 'document_id', 'payment_id');
    }

    public function categories(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'document_id');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'document_id');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class, 'document_id');
    }

    public function downloads(): HasMany
    {
        return $this->hasMany(Download::class, 'document_id');
    }

//    public function setSourceUrlAttribute($value)
//    {
//            $size = $value->getSize(); // Get the file size in bytes
//
//            // You can convert the size to a human-readable format if desired
//            $formattedSize = $this->formatSizeUnits($size);
//
//            $attribute_name = 'source_url';
//            $disk = "public";
//            $destination_path = 'pdftest';
//
//            $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
//            $this->attributes['disks'] = 'public';
//            $this->attributes['path'] = $this->source_url;
//            $this->attributes['source_url'] = $this->source_url;
//            $this->original_size = $size;
//            $this->original_format = $formattedSize;
//    }

    public function formatSizeUnits($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        $disk = $this->getAttribute('disks');
        $path = $this->getAttribute('source_url');
        if ($disk && $path) {
            return \Storage::disk($this->getAttribute('disks'))
                ->url($this->getAttribute('source_url'));
        }
        return $this->url;

    }

    public function deleteFromDisk(): ?string
    {
        $disk = $this->getAttribute('disks');
        $path = $this->getAttribute('path');
        if ($disk && $path) {
            return \Storage::disk($this->getAttribute('disks'))
                ->delete($path);
        }
        return null;
    }
}
