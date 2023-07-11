<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use CrudTrait;

    protected $table = 'comments';
    protected $guarded = ['id'];

    public function documents() {
        return $this->belongsTo(Document::class, 'document_id');
    }
}
