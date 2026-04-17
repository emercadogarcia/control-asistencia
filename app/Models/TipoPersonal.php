<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPersonal extends Model
{
    use HasFactory;

    protected $table = 'tipo_personal';
    protected $fillable = ['nombre', 'descripcion', 'estado'];
    public $timestamps = false;

    const CREATED_AT = 'creado_el';
    const UPDATED_AT = null;
}
