<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'კონცერტი', 'description' => 'მუსიკალური კონცერტები და შოუები'],
            ['name' => 'სპორტი', 'description' => 'სპორტული ღონისძიებები'],
            ['name' => 'კონფერენცია', 'description' => 'ბიზნეს კონფერენციები და სემინარები'],
            ['name' => 'თეატრი', 'description' => 'თეატრალური წარმოდგენები'],
            ['name' => 'კინო', 'description' => 'კინო ჩვენებები და ფესტივალები'],
            ['name' => 'ხელოვნება', 'description' => 'ხელოვნების გამოფენები'],
            ['name' => 'განათლება', 'description' => 'საგანმანათლებლო ღონისძიებები'],
            ['name' => 'საოჯახო', 'description' => 'ოჯახური ღონისძიებები'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
            ]);
        }
    }
}