<?php

namespace App\FiguresDetector\Entities;

use Illuminate\Support\Collection;

class FigureCollection extends Collection
{
    /**
     * The items contained in the collection.
     *
     * @var array|Figure[]
     */
    protected $items = [];
    
    /**
     * Get the collection of items as a plain array.
     *
     * @return array
     */
    public function toArray()
    {
        return parent::toArray();
    }
}