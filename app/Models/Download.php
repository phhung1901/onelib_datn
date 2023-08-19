<?php

namespace App\Models;

use App\Models\Traits\HasIdRangeScope;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PhpParser\Comment\Doc;

class Download extends Model
{
    use CrudTrait;
    use HasIdRangeScope;

    protected $table = 'downloads';
    protected $guarded = ['id'];
    protected $casts = [
        'payload' => 'json'
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

}
