<?php

namespace App\Models;

use App\Models\Traits\HasIdRangeScope;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use CrudTrait;
    use HasIdRangeScope;

    protected $table = 'tags';
    protected $guarded = ['id'];

    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Document::class, 'document_tag',  'tag_id', 'document_id');
    }
}
