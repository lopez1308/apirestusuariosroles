<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermisosUsuario extends Model
{
	protected $table = 'usuarios_permisos';
    protected $fillable = ['permiso_id','usuario_id'];
}
