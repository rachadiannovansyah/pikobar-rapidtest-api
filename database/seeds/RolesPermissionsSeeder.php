<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $accessDashboardPermission = Permission::create(['name' => 'backoffice.access', 'title' => 'Access Backoffice']);

        $usersListPermission = Permission::create(['name' => 'users.list', 'title' => 'List Users']);
        $usersReadPermission = Permission::create(['name' => 'users.read', 'title' => 'Read Users']);
        $usersCreatePermission = Permission::create(['name' => 'users.create', 'title' => 'Create Users']);
        $usersEditPermission = Permission::create(['name' => 'users.edit', 'title' => 'Edit Users']);
        $usersDeletePermission = Permission::create(['name' => 'users.delete', 'title' => 'Delete Users']);

        $casesListPermission = Permission::create(['name' => 'cases.list', 'title' => 'List Medical Cases']);
        $casesReadPermission = Permission::create(['name' => 'cases.read', 'title' => 'Read Medical Cases']);
        $casesCreatePermission = Permission::create(['name' => 'cases.create', 'title' => 'Create Medical Cases']);
        $casesEditPermission = Permission::create(['name' => 'cases.edit', 'title' => 'Edit Medical Cases']);
        $casesDeletePermission = Permission::create(['name' => 'cases.delete', 'title' => 'Delete Medical Cases']);

        // Administrator
        $role = Role::create(['name' => 'administrator', 'title' => 'Administrator']);
        $role->givePermissionTo(['backoffice.access']);
        $role->givePermissionTo(['users.list', 'users.read', 'users.create', 'users.edit', 'users.delete']);
        $role->givePermissionTo(['cases.list', 'cases.read', 'cases.create', 'cases.edit', 'cases.delete']);

        $role = Role::create(['name' => 'dinkes-provinsi-operator', 'title' => 'Operator Dinas Kesehatan Provinsi']);
        $role->givePermissionTo(['backoffice.access']);
        $role->givePermissionTo(['users.list', 'users.read', 'users.create', 'users.edit', 'users.delete']);
        $role->givePermissionTo(['cases.list', 'cases.read']);

        $role = Role::create(['name' => 'dinkes-kabkota-operator', 'title' => 'Operator Dinas Kesehatan Kabupaten/Kota']);
        $role->givePermissionTo(['backoffice.access']);
        $role->givePermissionTo(['cases.list', 'cases.read', 'cases.create', 'cases.edit', 'cases.delete']);

        $role = Role::create(['name' => 'rumahsakit-operator', 'title' => 'Operator Rumah Sakit']);
        $role->givePermissionTo(['backoffice.access']);

        $role = Role::create(['name' => 'puskesmas-operator', 'title' => 'Operator Puskesmas']);
        $role->givePermissionTo(['backoffice.access']);

        $role = Role::create(['name' => 'labkes-provinsi-operator', 'title' => 'Operator Laboratorium Kesehatan Provinsi']);
        $role->givePermissionTo(['backoffice.access']);

        $role = Role::create(['name' => 'labkes-kabkota-operator', 'title' => 'Operator Laboratorium Kesehatan Kabupaten/Kota']);
        $role->givePermissionTo(['backoffice.access']);
    }
}
