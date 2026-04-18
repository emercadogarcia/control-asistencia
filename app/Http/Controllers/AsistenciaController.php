<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Personal;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AsistenciaController extends Controller
{
    public function index()
    {
        return view('asistencia.index', $this->buildAttendanceViewData());
    }

    public function crear()
    {
        return view('asistencia.index', $this->buildAttendanceViewData());
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
            'sucursal' => $turnoVigente->turno->sucursal,
        ]);
    }

    public function marcar(Request $request)
    {
        $validated = $request->validate([
            'ci' => 'nullable|string|required_without:personal_id',
            'personal_id' => 'nullable|exists:personal,id',
            'tipo' => 'nullable|in:entrada,salida',
            'timezone' => 'nullable|string',
        ]);

        $personal = !empty($validated['personal_id'])
            ? Personal::where('estado', 1)->findOrFail($validated['personal_id'])
            : Personal::where('ci', trim((string) $validated['ci']))
                ->where('estado', 1)
                ->first();

        if (!$personal) {
            return response()->json(['error' => 'No existe personal activo con ese CI'], 404);
        }

        $turnoVigente = $personal->turnoVigente;
        if (!$turnoVigente || !$turnoVigente->turno) {
            return response()->json(['error' => 'El personal no tiene turno asignado'], 400);
        }

        $timezone = $validated['timezone'] ?? config('app.timezone', 'UTC');
        if (!in_array($timezone, timezone_identifiers_list(), true)) {
            $timezone = config('app.timezone', 'UTC');
        }

        $ahora = Carbon::now($timezone);
        $hoy = $ahora->toDateString();

        $asistencia = Asistencia::where('personal_id', $personal->id)
            ->whereDate('fecha', $hoy)
            ->first();

        $tipo = $validated['tipo'] ?? $this->resolveAttendanceType($asistencia);

        if ($tipo === 'completo') {
            return response()->json([
                'error' => 'La asistencia de hoy ya fue completada',
                'tipo' => 'completo',
            ], 400);
        }

        if ($tipo === 'entrada') {
            if ($asistencia) {
                return response()->json(['error' => 'Ya tiene marcacion de entrada hoy'], 400);
            }

            $horaEntrada = (string) $turnoVigente->turno->hora_entrada;
            $tolerancia = $turnoVigente->turno->tolerancia_min ?? 10;
            $horaProgramada = $ahora->copy()->setTimeFromTimeString(substr($horaEntrada, 0, 8));
            $minutosDiferencia = (int) floor(($ahora->getTimestamp() - $horaProgramada->getTimestamp()) / 60);

            $estado = $minutosDiferencia > $tolerancia
                ? Asistencia::ESTADO_TARDANZA
                : Asistencia::ESTADO_PRESENTE;

            $asistencia = Asistencia::create([
                'personal_id' => $personal->id,
                'fecha' => $hoy,
                'hora_entrada' => $ahora,
                'estado' => $estado,
                'ip_dispositivo' => $request->ip(),
            ]);

            return response()->json([
                'message' => 'Entrada registrada correctamente',
                'tipo' => 'entrada',
                'estado' => $estado,
                'asistencia' => $asistencia,
                'personal' => [
                    'nombre' => $personal->nombre,
                    'apellido' => $personal->apellido,
                    'ci' => $personal->ci,
                ],
            ]);
        }

        if (!$asistencia) {
            return response()->json(['error' => 'No hay entrada registrada hoy'], 400);
        }

        if ($asistencia->hora_salida) {
            return response()->json(['error' => 'La salida ya fue registrada hoy'], 400);
        }

        $asistencia->update(['hora_salida' => $ahora]);

        return response()->json([
            'message' => 'Salida registrada correctamente',
            'tipo' => 'salida',
            'estado' => $asistencia->estado,
            'asistencia' => $asistencia,
            'personal' => [
                'nombre' => $personal->nombre,
                'apellido' => $personal->apellido,
                'ci' => $personal->ci,
            ],
        ]);
    }

    public function reporteDiario()
    {
        $inicio = request()->input('inicio', today());
        $fin = request()->input('fin', today());
        $sucursal_id = request()->input('sucursal_id');

        $query = Asistencia::with(['personal' => function ($q) {
            $q->with('turnoVigente.turno');
        }])->whereBetween('fecha', [$inicio, $fin]);

        if ($sucursal_id) {
            $query->whereHas('personal', function ($q) use ($sucursal_id) {
                $q->where('sucursal_id', $sucursal_id);
            });
        }

        $asistencias = $query->orderBy('fecha', 'desc')->get();

        return response()->json($asistencias);
    }

    public function exportarExcel()
    {
        return response()->json(['error' => 'Exportacion no disponible aun'], 501);
    }

    private function resolveAttendanceType(?Asistencia $asistencia): string
    {
        if (!$asistencia) {
            return 'entrada';
        }

        if (!$asistencia->hora_salida) {
            return 'salida';
        }

        return 'completo';
    }

    private function buildAttendanceViewData(): array
    {
        $hoy = today();
        $asistencias = Asistencia::with(['personal.sucursal'])
            ->whereDate('fecha', $hoy)
            ->orderByDesc('hora_entrada')
            ->paginate(20);

        return [
            'asistencias' => $asistencias,
            'totalHoy' => Asistencia::whereDate('fecha', $hoy)->count(),
            'presentesHoy' => Asistencia::whereDate('fecha', $hoy)
                ->where('estado', Asistencia::ESTADO_PRESENTE)
                ->count(),
            'tardanzasHoy' => Asistencia::whereDate('fecha', $hoy)
                ->where('estado', Asistencia::ESTADO_TARDANZA)
                ->count(),
            'pendientesSalida' => Asistencia::whereDate('fecha', $hoy)
                ->whereNull('hora_salida')
                ->count(),
        ];
    }
}
