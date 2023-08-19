<?php

namespace App\Models;

use App\Models\Traits\HasIdRangeScope;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Payment extends Model
{
    use CrudTrait;
    use HasIdRangeScope;

    protected $table = 'payments';
    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Document::class, 'payment_documents', 'payment_id', 'document_id');
    }
}
