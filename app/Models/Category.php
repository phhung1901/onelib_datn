<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use CrudTrait;

    protected $table = 'categories';
    protected $fillable = [
      'id',
      'name',
      'slug'
    ];
    protected $guarded = ['id'];

    public function documents()
    {
        return $this->hasMany(Document::class, 'category_id');
    }
}
