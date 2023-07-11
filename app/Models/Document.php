<?php

namespace App\Models;

use App\Libs\SeoSlugGenerator;
use App\Models\Traits\HasIdRangeScope;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Document extends Model
{
    use CrudTrait;
    use HasIdRangeScope;

    protected $table = 'documents';
    protected $fillable = [
        'user_id',
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

//    protected $fillable = [
//        'source_url',
//    ];
    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model){
            if ($model->price === null) {
                $model->price = 0;
            }
            $model->user_id = backpack_user()->id;
            $model->slug = Str::slug((new SeoSlugGenerator($model->title))->run());
        });

        static::updating(function ($model) {
            $oldImage = $model->getOriginal('source_url');
            $oldImage = str_replace('storage/', '', $oldImage);

            $newImage = $model->getAttribute('source_url');
            if ($oldImage !== $newImage) {
                \Storage::disk('public')->delete($oldImage);
            }
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

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'document_category', 'document_id', 'category_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'document_id');
    }

    public function setSourceUrlAttribute($value)
    {
        $size = $value->getSize(); // Get the file size in bytes

        // You can convert the size to a human-readable format if desired
        $formattedSize = $this->formatSizeUnits($size);

        $attribute_name = 'source_url';
        $disk = "public";
        $destination_path = 'pdftest';

        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
        $this->attributes['disks'] = 'public';
        $this->attributes['path'] = $this->source_url;
        $this->attributes['source_url'] = 'storage/' . $this->source_url;
        $this->original_size = $size;
        $this->original_format = $formattedSize;
//        Storage::disk($disk)->move($this->attributes['source_url'], $destination_path . '/' . $new_filename);

    }

    private function formatSizeUnits($bytes)
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
        $path = $this->getAttribute('path');
        if ($disk && $path) {
            return \Storage::disk($this->getAttribute('disks'))
                ->url($this->getAttribute('path'));
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
