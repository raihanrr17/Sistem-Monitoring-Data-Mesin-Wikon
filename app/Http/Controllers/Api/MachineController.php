<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Service\DashboardService;
use App\Models\Machine;

class MachineController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    // ── GET /api/dashboard?bulan=...&tahun=... ──────────────────────────────

    public function dashboard(Request $request)
    {
        $request->validate([
            'bulan' => ['nullable', 'string', 'max:20'],
            'tahun' => ['nullable', 'integer', 'min:2000', 'max:2100'],
        ]);

        $data = $this->dashboardService->getDashboardData(
            $request->bulan,
            $request->tahun
        );

        return response()->json([
            'status' => 'success',
            'data'   => $data,
        ]);
    }

    // ── GET /api/machines?bulan=...&tahun=... ───────────────────────────────

    public function index(Request $request)
    {
        $request->validate([
            'bulan' => ['nullable', 'string', 'max:20'],
            'tahun' => ['nullable', 'integer', 'min:2000', 'max:2100'],
        ]);

        $query = Machine::query();

        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        return response()->json($query->orderBy('id', 'desc')->get());
    }

    // ── POST /api/machines/store ────────────────────────────────────────────

   public function store(Request $request)
{
    $validated = $request->validate([
        'bulan'              => ['required', 'string', 'max:20'],
        'tahun'              => ['required', 'integer', 'min:2000', 'max:2100'],
        'plant'              => ['required', 'string', 'max:10'],
        'kode_mesin'         => ['required', 'string', 'max:20'],
        'loading_time'       => ['required', 'numeric', 'min:0'],
        'operating_time'     => ['required', 'numeric', 'min:0'],
        'breakdown_time'     => ['nullable', 'numeric', 'min:0'],
        'freq_breakdown'     => ['nullable', 'numeric', 'min:0'],  // ← integer → numeric
        'masalah'            => ['nullable', 'string'],            // ← required → nullable
        'langkah_perbaikan'  => ['nullable', 'string'],            // ← required → nullable
        'langkah_pencegahan' => ['nullable', 'string'],            // ← required → nullable
        'availability'       => ['required', 'numeric'],           // ← hapus max:10, string → numeric
        'mtbf'               => ['required', 'numeric'],           // ← string → numeric
        'mttr'               => ['required', 'numeric'],           // ← string → numeric
        'status'             => ['required', 'string', 'max:20'],
    ]);

    $validated['breakdown_time']      = $validated['breakdown_time']      ?? 0;
    $validated['freq_breakdown']      = $validated['freq_breakdown']       ?? 0;
    $validated['masalah']             = $validated['masalah']              ?? '-';
    $validated['langkah_perbaikan']   = $validated['langkah_perbaikan']   ?? '-';
    $validated['langkah_pencegahan']  = $validated['langkah_pencegahan']  ?? '-';

    $machine = Machine::create($validated);

    return response()->json([
        'status'  => 'success',
        'message' => 'Data berhasil disimpan.',
        'data'    => $machine,
    ], 201);
}
    // ── PUT /api/machines/{id} ──────────────────────────────────────────────

    public function update(Request $request, $id)
    {
        $machine = Machine::findOrFail($id);

        $validated = $request->validate([
            'bulan'              => ['required', 'string', 'max:20'],
            'tahun'              => ['required', 'integer', 'min:2000', 'max:2100'],
            'plant'              => ['required', 'string', 'max:10'],
            'kode_mesin'         => ['required', 'string', 'max:20'],
            'loading_time'       => ['required', 'numeric', 'min:0'],
            'operating_time'     => ['required', 'numeric', 'min:0'],
            'breakdown_time'     => ['nullable', 'numeric', 'min:0'],
            'freq_breakdown'     => ['nullable', 'integer', 'min:0'],
            'masalah'            => ['required', 'string'],
            'langkah_perbaikan'  => ['required', 'string'],
            'langkah_pencegahan' => ['required', 'string'],
            'availability'       => ['required', 'string', 'max:10'],
            'mtbf'               => ['required', 'string', 'max:10'],
            'mttr'               => ['required', 'string', 'max:10'],
            'status'             => ['required', 'string', 'max:20'],
        ]);

        $machine->update($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data berhasil diupdate.',
            'data'    => $machine->fresh(),
        ]);
    }

    // ── DELETE /api/machines/{id} ───────────────────────────────────────────

    public function destroy($id)
    {
        $machine = Machine::findOrFail($id);
        $machine->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Data berhasil dihapus.',
        ]);
    }
}