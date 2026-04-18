<?php

namespace App\Http\Controllers;

use App\Exports\ReporteAsistenciaExport;
use App\Services\ReporteAsistenciaService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReporteController extends Controller
{
    public function __construct(
        private readonly ReporteAsistenciaService $reporteAsistenciaService
    ) {
    }

    public function asistenciaDiaria(Request $request)
    {
        return $this->respond($request, 'asistencia_diaria');
    }

    public function atrasos(Request $request)
    {
        return $this->respond($request, 'atrasos');
    }

    public function horasTrabajadas(Request $request)
    {
        return $this->respond($request, 'horas_trabajadas');
    }

    public function horasExtra(Request $request)
    {
        return $this->respond($request, 'horas_extra');
    }

    public function inasistencias(Request $request)
    {
        return $this->respond($request, 'inasistencias');
    }

    private function respond(Request $request, string $type)
    {
        $filters = $request->validate([
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
            'export' => 'nullable|in:excel,pdf',
        ]);

        $report = $this->reporteAsistenciaService->getReport($type, $filters);

        if (($filters['export'] ?? null) === 'excel') {
            return Excel::download(
                new ReporteAsistenciaExport($report['rows'], $report['headings']),
                $report['filename'] . '.xlsx'
            );
        }

        if (($filters['export'] ?? null) === 'pdf') {
            return Pdf::loadView('reportes.asistencia-pdf', [
                'title' => $report['title'],
                'headings' => $report['headings'],
                'rows' => $report['rows'],
                'filters' => $report['filters'],
            ])->download($report['filename'] . '.pdf');
        }

        return response()->json($report['data']);
    }
}
