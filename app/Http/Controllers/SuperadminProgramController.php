<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Program;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SuperadminProgramController extends Controller
{
    /**
     * Display a listing of course programs with metrics and syllabus inspector.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $search = trim((string) $request->query('q', ''));
        $statusFilter = $request->query('status', 'all');
        $categoryFilter = $request->query('kategori', 'all');

        $query = Program::query()
            ->withCount([
                'jadwals as total_jadwal_count',
                'jadwals as jadwal_aktif_count' => function ($q) {
                    $q->where('status', '!=', 'dibatalkan');
                },
            ])
            ->with(['jadwals' => function ($q) {
                $q->where('status', '!=', 'dibatalkan')
                    ->whereDate('tanggal', '>=', Carbon::today())
                    ->orderBy('tanggal')
                    ->orderBy('jam_mulai')
                    ->with(['cabang', 'tentor'])
                    ->limit(6);
            }]);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama_program', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        if (in_array($statusFilter, ['aktif', 'nonaktif'], true)) {
            $query->where('status', $statusFilter);
        }

        if ($categoryFilter !== 'all' && $categoryFilter !== '') {
            $query->where('kategori', $categoryFilter);
        }

        $programs = $query->orderBy('nama_program')->get();

        // Metrics
        $totalProgram = Program::count();
        $programAktif = Program::where('status', 'aktif')->count();
        $programNonaktif = Program::where('status', 'nonaktif')->count();
        $categories = Program::select('kategori')->whereNotNull('kategori')->where('kategori', '!=', '')->distinct()->pluck('kategori');
        $totalKategori = $categories->count();
        $totalJadwalTerkait = Jadwal::where('status', '!=', 'dibatalkan')->count();

        // Selected program for inspector
        $selectedProgramId = $request->query('selected');
        $selectedProgram = null;
        if ($selectedProgramId) {
            $selectedProgram = $programs->firstWhere('id', (int) $selectedProgramId);
        }
        if (!$selectedProgram && $programs->isNotEmpty()) {
            $selectedProgram = $programs->first();
        }

        return view('superadmin.program.index', [
            'user' => $user,
            'programs' => $programs,
            'selectedProgram' => $selectedProgram,
            'search' => $search,
            'statusFilter' => $statusFilter,
            'categoryFilter' => $categoryFilter,
            'categories' => $categories,
            'totalProgram' => $totalProgram,
            'programAktif' => $programAktif,
            'programNonaktif' => $programNonaktif,
            'totalKategori' => $totalKategori,
            'totalJadwalTerkait' => $totalJadwalTerkait,
        ]);
    }

    /**
     * Store a newly created program in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_program' => 'required|string|max:150|unique:program,nama_program',
            'kategori' => 'nullable|string|max:100',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'nama_program.required' => 'Nama program kursus wajib diisi.',
            'nama_program.unique' => 'Nama program kursus sudah terdaftar.',
            'status.in' => 'Status program harus aktif atau nonaktif.',
        ]);

        $program = Program::create($validated);

        return redirect()
            ->route('superadmin.program.index', ['selected' => $program->id])
            ->with('success', "Program kursus '{$program->nama_program}' berhasil ditambahkan.");
    }

    /**
     * Update the specified program in storage.
     */
    public function update(Request $request, Program $program): RedirectResponse
    {
        $validated = $request->validate([
            'nama_program' => [
                'required',
                'string',
                'max:150',
                Rule::unique('program', 'nama_program')->ignore($program->id),
            ],
            'kategori' => 'nullable|string|max:100',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'nama_program.required' => 'Nama program kursus wajib diisi.',
            'nama_program.unique' => 'Nama program kursus sudah digunakan oleh program lain.',
            'status.in' => 'Status program harus aktif atau nonaktif.',
        ]);

        $program->update($validated);

        return redirect()
            ->route('superadmin.program.index', ['selected' => $program->id])
            ->with('success', "Program kursus '{$program->nama_program}' berhasil diperbarui.");
    }

    /**
     * Toggle active/inactive status of the program.
     */
    public function toggleStatus(Program $program): RedirectResponse
    {
        $newStatus = $program->status === 'aktif' ? 'nonaktif' : 'aktif';
        $program->update(['status' => $newStatus]);

        $label = $newStatus === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Program '{$program->nama_program}' berhasil {$label}.");
    }

    /**
     * Remove the specified program from storage if no relation exists.
     */
    public function destroy(Program $program): RedirectResponse
    {
        if ($program->jadwals()->exists()) {
            return back()->with('error', "Program kursus '{$program->nama_program}' tidak dapat dihapus permanen karena sudah memiliki riwayat jadwal (PRD 14.2 Relational Guard). Silakan nonaktifkan status program.");
        }

        $nama = $program->nama_program;
        $program->delete();

        return redirect()
            ->route('superadmin.program.index')
            ->with('success', "Program kursus '{$nama}' berhasil dihapus permanen.");
    }
}
