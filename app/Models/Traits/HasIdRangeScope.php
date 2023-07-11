<?php


namespace App\Models\Traits;


use Illuminate\Database\Eloquent\Builder;

trait HasIdRangeScope
{
    public function scopeIdRange(Builder $query, $id) {
        return $query->when($id, function (Builder $query) use ($id) {
            if (strpos($id, '-')) {
                [$min, $max] = explode("-", $id, 2);
                if (!$min && !$max) {
                    throw new \InvalidArgumentException("Out of accepted range: $id");
                }
                if ($min) {
                    $query->where('id', ">=", $min);
                }
                if ($max) {
                    $query->where('id', "<=", $max);
                }
            } elseif (strpos($id, ',')) {
                $ids = explode(',', $id);
                $query->whereIn('id', $ids);
            } else {
                $query->where('id', $id);
            }
        });
    }
}
