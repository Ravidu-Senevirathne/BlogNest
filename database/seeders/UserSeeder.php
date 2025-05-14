<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Creates default users for testing purposes.
     */
    public function run(): void
    {
        $this->createEditorUser();
        $this->createReaderUser();
    }

    /**
     * Create a default editor user.
     */
    private function createEditorUser(): void
    {
        $editor = User::create([
            'name' => 'Editor User',
            'email' => 'editor@example.com',
            'password' => Hash::make('password'),
        ]);

        $editor->assignRole('editor');
    }

    /**
     * Create a default reader user.
     */
    private function createReaderUser(): void
    {
        $reader = User::create([
            'name' => 'Reader User',
            'email' => 'reader@example.com',
            'password' => Hash::make('password'),
        ]);

        $reader->assignRole('reader');
    }
}
