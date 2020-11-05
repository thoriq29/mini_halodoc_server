<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Models\User;

class AppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            "Manage Role",
            "Create Role",
            "Edit Role",
            "Delete Role",
            "Manage Users",
            "Add Users",
            "Edit Users",
            "Delete Users",
        ];

        $this->savePermissions($permissions);
        

        $admin = User::create([
        	'name' => 'Admin',
            'email' => 'admin@halodoc.id',
        	'password' => bcrypt('changemeplease')
        ]);

        $role = Role::create([
            'name' => 'Admin',
            'slug' => 'admin'
        ]);
        $usrRole = Role::create([
            'name' => 'Patient',
            'slug' => 'patient'
        ]);

        $permissions = Permission::pluck('id','id')->all();
        $role->permissions()->attach($permissions);
        $admin->roles()->attach($role);
        $admin->createToken('halodoc')->accessToken;

        $permissions = [
            'Accept Consultation',
            'Cancel Consultation',
        ];

        $docRole = Role::create([
            'name' => 'Doctor',
            'slug' => 'doctor'
        ]);

        $this->savePermissions($permissions);

        $permissions = Permission::pluck('id','id')->all();
        $docRole->permissions()->attach($permissions);
    }

    public function savePermissions($permissions)
    {
        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                [
                    'name' => $permission,
                    'slug' => strtolower(str_replace(' ', '-', $permission))
                ]
            );
        }
    }
}
