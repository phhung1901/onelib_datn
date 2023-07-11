<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $created = 0;
        $categories = \Symfony\Component\Yaml\Yaml::parse(file_get_contents(__DIR__ . '/category.yml'))['default'];
        foreach ($categories as $category){
            $category = Category::create($category);
            if ($category){
                $created++;
            }
        }
        $this->command->info("Created " . $created . " topic(s)");
    }
}
