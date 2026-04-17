<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarioLaboral extends Model
{
    use HasFactory;

    protected $table = 'calendario_laboral';
    protected $fillable = ['sucursal_id', 'fecha', 'es_feriado', 'descripcion'];
    protected $casts = ['fecha' => 'date', 'es_feriado' => 'boolean'];
    public $timestamps = false;

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }
}
