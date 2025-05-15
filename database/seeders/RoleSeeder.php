<?php

namespace Database\Seeders;

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
        // roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'editor']);
        Role::create(['name' => 'reader']);

        //  permissions 
        Permission::create(['name' => 'create posts']);
        Permission::create(['name' => 'edit posts']);
        Permission::create(['name' => 'delete posts']);
        Permission::create(['name' => 'publish posts']);
        Permission::create(['name'=>'comment posts']);

        // Assign permissions to roles
        $editorRole = Role::findByName('editor');
        $editorRole->givePermissionTo('create posts');
        $editorRole->givePermissionTo('edit posts');
        $editorRole->givePermissionTo('delete posts');

        $adminRole = Role::findByName('admin');
        $adminRole->givePermissionTo('create posts');
        $adminRole->givePermissionTo('edit posts');
        $adminRole->givePermissionTo('delete posts');
        $adminRole->givePermissionTo('publish posts');

        $readerRole=Role::findByName('reader');
        $readerRole->givePermissionTo('comment posts');

    }
}
