<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions 
        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'edit user']);
        Permission::create(['name' => 'delete user']);
        Permission::create(['name' => 'create permission']);
        Permission::create(['name' => 'edit permission']);
        Permission::create(['name' => 'delete permission']);
        Permission::create(['name' => 'set permission role']);
        Permission::create(['name' => 'create role']);
        Permission::create(['name' => 'edit role']);
        Permission::create(['name' => 'delete role']);
        Permission::create(['name' => 'set role user']);
        
        // create roles and assign existing permissions
        $role1 = Role::create(['name' => 'user']);
        
        $role2 = Role::create(['name' => 'admin']);
        $role2->syncPermissions(['create user', 'edit user', 'delete user']);
        
        $role3 = Role::create(['name' => 'super-admin']);
        // gets all permissions via Gate::before rule; see AuthServiceProvider 
        
        // create user and assign existing roles 
        User::create([
            'name' => 'holiq ibrahim',
            'email' => 'holiq.ibrahim376@gmail.com',
            'password' => Hash::make('11111111'),
        ])->assignRole('super-admin');
        
        User::create([
            'name' => 'holiq',
            'email' => 'holiq@gmail.com',
            'password' => Hash::make('11111111'),
        ])->assignRole('admin');
        
        User::create([
            'name' => 'liq',
            'email' => 'liqun@gmail.com',
            'password' => Hash::make('11111111'),
        ])->assignRole('user');
    }
}
