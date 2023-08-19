<?php

namespace App\Models;

use App\Models\Traits\HasIdRangeScope;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use CrudTrait;
    use HasIdRangeScope;

    protected $table = 'comments';
    protected $guarded = ['id'];

    public function users() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function documents() {
        return $this->belongsTo(Document::class, 'document_id');
    }
}
