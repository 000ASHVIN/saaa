<?php

use App\Users\Role;
use App\Users\User;
use App\Users\Profile;
use App\Users\Permission;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Admin Accounts
        $gerhard = factory(User::class)->create([ 'first_name' => 'Gerhard', 'last_name' => 'Theunissen', 'email' => 'gerhardt@saiba.org.za', 'password' => 'P@ssw0rd' ]);
    	$tiaan = factory(User::class)->create([ 'first_name' => 'Tiaan', 'last_name' => 'Theunissen', 'email' => 'tiaant@saiba.org.za', 'password' => 'P@ssw0rd' ]);
        $hendrik = factory(User::class)->create([ 'first_name' => 'Hendrik', 'last_name' => 'Grobler', 'email' => 'it@accountingacademy.co.za', 'password' => 'P@ssw0rd' ]);

        $gerhard->profile()->save(new Profile);
        $tiaan->profile()->save(new Profile);
        $hendrik->profile()->save(new Profile);

        // Create Roles & Permissions
        $role = factory(Role::class)->create();
        $permission = factory(Permission::class)->create();
        $role->givePermissionTo($permission);

        // Assign Roles
        $gerhard->assignRole($role->name);
        $tiaan->assignRole($role->name);
        $hendrik->assignRole($role->name);
    }
}
