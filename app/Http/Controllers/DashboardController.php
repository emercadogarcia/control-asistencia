<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Personal;
use App\Models\Sucursal;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = today();

        // Estadísticas del día
        $totalPersonal = Personal::where('estado', 1)->count();
        $presentesHoy = Asistencia::whereDate('fecha', $hoy)
            ->where('estado', Asistencia::ESTADO_PRESENTE)->count();
        $tardanzasHoy = Asistencia::whereDate('fecha', $hoy)
            ->where('estado', Asistencia::ESTADO_TARDANZA)->count();
        $auentesHoy = $totalPersonal - $presentesHoy - $tardanzasHoy;

        // Registros recientes
        $asistenciasRecientes = Asistencia::with('personal')
            ->whereDate('fecha', $hoy)
            ->orderBy('hora_entrada', 'desc')
            ->limit(10)
            ->get();

        // Por sucursal
        $sucursales = Sucursal::where('estado', 1)
            ->with(['personals' => function($q) {
                $q->where('estado', 1);
            }])
            ->get();

        return view('dashboard', compact(
            'totalPersonal',
            'presentesHoy',
            'tardanzasHoy',
            'auentesHoy',
            'asistenciasRecientes',
            'sucursales',
            'hoy'
        ));
    }
}
