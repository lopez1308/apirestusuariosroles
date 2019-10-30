<?php

use Illuminate\Database\Seeder;

class permisosRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles_permisos')->insert([
        	['role_id' => 1,'permiso_id' => 2],
        	['role_id' => 2,'permiso_id' => 2],
        	['role_id' => 3,'permiso_id' => 2],
        ]);    }
}
