<?php

use App\Users\User;
use Bican\Roles\Models\Permission;
use Bican\Roles\Models\Role;
use Illuminate\Database\Seeder;

class AdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'This is the Administrator', // optional
            'level' => 1, // optional, set to 1 by default
        ]);

        $superUser = Role::create([
            'name' => 'Super',
            'slug' => 'super',
            'description' => 'This is the Super user for this website, This Role can do anything',
            'level' => '10'
        ]);

        $adminSection = Permission::create([
            'name' => 'Access Admin section',
            'slug' => 'access-admin-section',
            'description' => 'This will allow the user to access the admin section'
        ]);

        $adminRole->permissions()->attach($adminSection);
    
        // find the current users
        $gerhard = User::find(1);
        $tiaan = User::find(2);

        // Normal Admin Account
        $gerhard->attachRole($adminRole);
        $tiaan->attachRole($adminRole);

        // Also make them super users
        $gerhard->attachRole($superUser);
        $tiaan->attachRole($superUser);
    }
}
