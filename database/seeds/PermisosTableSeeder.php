<?php

use Illuminate\Database\Seeder;

class PermisosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permisos')->insert([
        	['nombre' => 'Crear'],
        	['nombre' => 'Leer'],
        	['nombre' => 'Actualizar'],
        	['nombre' => 'Eliminar'],
        	['nombre' => 'Copiar'],
        	['nombre' => 'Activar']
        ]);
    }
}
