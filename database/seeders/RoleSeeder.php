<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $editorRole = Role::create(['name' => 'editor']);
        $readerRole = Role::create(['name' => 'reader']);

        // Create permissions
        $permissions = [
            // Admin permissions
            'manage users',
            'manage roles',
            'manage settings',

            // Editor permissions
            'create posts',
            'edit posts',
            'delete posts',
            'publish posts',
            'manage categories',
            'manage tags',

            // Reader permissions
            'view posts',
            'create comments',
            'delete own comments',
            'like posts',
            'bookmark posts',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to admin role
        $adminRole->givePermissionTo(Permission::all());

        // Assign permissions to editor role
        $editorRole->givePermissionTo([
            'create posts',
            'edit posts',
            'delete posts',
            'publish posts',
            'manage categories',
            'manage tags',
            'view posts',
            'create comments',
            'delete own comments',
            'like posts',
            'bookmark posts',
        ]);

        // Assign permissions to reader role
        $readerRole->givePermissionTo([
            'view posts',
            'create comments',
            'delete own comments',
            'like posts',
            'bookmark posts',
        ]);
    }
}
