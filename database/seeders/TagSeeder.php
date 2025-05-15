<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tags')->insert([
            ['name' => 'Laravel', 'slug' => 'laravel', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'PHP', 'slug' => 'php', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'JavaScript', 'slug' => 'javascript', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Vue.js', 'slug' => 'vuejs', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'React', 'slug' => 'react', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Database', 'slug' => 'database', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tips', 'slug' => 'tips', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tutorial', 'slug' => 'tutorial', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
