<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class usuarios extends Model
{
    protected $fillable = ['username','email','names','paternal_surname','maternal_surname','age','role','activo'];
}
