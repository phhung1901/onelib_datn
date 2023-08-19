<?php

namespace App\FiguresDetector\Entities;

use Illuminate\Contracts\Support\Arrayable;

class Figure implements Arrayable
{
    public int $page;
    public int $page_width;
    public int $page_height;
    
    public string $type;
    
    public string $caption;
    public float $caption_reliability;
    public array $caption_position = [];

    public array $context = [];
    
    public array $position = [];
    public float $reliability;
    public int $score = 0; // 0->40
    
    /**
     * @param array $data
     * @return static
     */
    public static function create(array $data = []) {
        return new static($data);
    }
    
    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
    
    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }
}