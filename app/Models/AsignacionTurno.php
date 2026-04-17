<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignacionTurno extends Model
{
    use HasFactory;

    protected $table = 'asignacion_turno';
    protected $fillable = ['personal_id', 'turno_id', 'fecha_inicio', 'fecha_fin', 'estado'];
    protected $casts = ['fecha_inicio' => 'date', 'fecha_fin' => 'date'];
    public $timestamps = false;

    public function personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }

    public function turno()
    {
        return $this->belongsTo(Turno::class, 'turno_id');
    }
}
