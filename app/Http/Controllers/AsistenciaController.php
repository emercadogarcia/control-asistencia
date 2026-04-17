<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Personal;
use App\Models\Sucursal;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AsistenciaController extends Controller
{
    public function index()
    {
        $asistencias = Asistencia::with('personal')
            ->whereDate('fecha', today())
            ->orderBy('hora_entrada', 'desc')
            ->paginate(20);

        return view('asistencia.index', compact('asistencias'));
    }

    public function crear()
    {
        $sucursales = Sucursal::where('estado', 1)->get();
        return view('asistencia.crear', compact('sucursales'));
    }

    public function buscarPersonal(Request $request)
    {
        $ci = $request->input('ci');
        
        $personal = Personal::where('ci', $ci)
            ->where('estado', 1)
            ->with('turnoVigente.turno.sucursal')
            ->first();

        if (!$personal) {
            return response()->json(['error' => 'Personal no encontrado'], 404);
        }

        $turnoVigente = $personal->turnoVigente;
        if (!$turnoVigente) {
            return response()->json(['error' => 'Personal no tiene turno asignado'], 400);
        }

        return response()->json([
            'personal' => $personal,
            'turno' => $turnoVigente->turno,
            'sucursal' => $turnoVigente->turno->sucursal
        ]);
    }

    public function marcar(Request $request)
    {
        $validated = $request->validate([
            'personal_id' => 'required|exists:personals,id',
            'tipo' => 'required|in:entrada,salida',
        ]);

        $personal = Personal::findOrFail($validated['personal_id']);
        $turnoVigente = $personal->turnoVigente;

        if (!$turnoVigente) {
            return response()->json(['error' => 'Sin turno asignado'], 400);
        }

        $hoy = today();
        $asistencia = Asistencia::where('personal_id', $personal->id)
            ->whereDate('fecha', $hoy)
            ->first();

        if ($validated['tipo'] === 'entrada') {
            if ($asistencia) {
                return response()->json(['error' => 'Ya tiene marcación de entrada hoy'], 400);
            }

            // Validar tolerancia
            $horaEntrada = $turnoVigente->turno->hora_entrada;
            $ahora = now();
            $tolerancia = $turnoVigente->turno->tolerancia_min ?? 10;
            
            $horaEntradaObj = Carbon::createFromTimeString($horaEntrada);
            $diferencia = $ahora->diffInMinutes($horaEntradaObj, false);

            $estado = Asistencia::ESTADO_PRESENTE;
            if ($diferencia > $tolerancia) {
                $estado = Asistencia::ESTADO_TARDANZA;
            }

            $asistencia = Asistencia::create([
                'personal_id' => $personal->id,
                'fecha' => $hoy,
                'hora_entrada' => $ahora,
                'estado' => $estado,
                'ip_dispositivo' => $request->ip(),
                'registrado_por' => auth()->id(),
            ]);

            return response()->json([
                'message' => 'Entrada registrada',
                'estado' => $estado,
                'asistencia' => $asistencia
            ]);
        } else {
            if (!$asistencia) {
                return response()->json(['error' => 'No hay entrada registrada hoy'], 400);
            }

            if ($asistencia->hora_salida) {
                return response()->json(['error' => 'Ya tiene salida registrada'], 400);
            }

            $asistencia->update(['hora_salida' => now()]);

            return response()->json([
                'message' => 'Salida registrada',
                'asistencia' => $asistencia
            ]);
        }
    }

    public function reporteDiario()
    {
        $inicio = request()->input('inicio', today());
        $fin = request()->input('fin', today());
        $sucursal_id = request()->input('sucursal_id');

        $query = Asistencia::with(['personal' => function($q) {
            $q->with('turnoVigente.turno');
        }])
            ->whereBetween('fecha', [$inicio, $fin]);

        if ($sucursal_id) {
            $query->whereHas('personal', function($q) use ($sucursal_id) {
                $q->where('sucursal_id', $sucursal_id);
            });
        }

        $asistencias = $query->orderBy('fecha', 'desc')->get();

        return response()->json($asistencias);
    }

    public function exportarExcel()
    {
        // TODO: Implementar exportación a Excel usando una librería como maatwebsite/excel
        // Por ahora retornar error
        return response()->json(['error' => 'Exportación no disponible aún'], 501);
    }
}
