<?php

namespace App\Models;

use App\Models\Traits\HasIdRangeScope;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use CrudTrait;
    use HasIdRangeScope;


    protected $table = 'reports';
    protected $guarded = ['id'];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id');
    }
}
