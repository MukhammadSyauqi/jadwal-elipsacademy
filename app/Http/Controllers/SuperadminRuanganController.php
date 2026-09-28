<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Jadwal;
use App\Models\Ruangan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SuperadminRuanganController extends Controller
{
    /**
     * Display a listing of rooms with metrics and inspector details.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $search = trim((string) $request->query('q', ''));
        $cabangFilter = $request->query('cabang_id', 'all');
        $statusFilter = $request->query('status', 'all');

        $query = Ruangan::with('cabang')
            ->withCount([
                'jadwals as total_jadwal_count',
                'jadwals as jadwal_aktif_count' => function ($q) {
                    $q->where('status', '!=', 'dibatalkan');
                },
            ])
            ->with(['jadwals' => function ($q) {
                $q->where('status', '!=', 'dibatalkan')
                    ->orderByDesc('tanggal')
                    ->orderByDesc('jam_mulai')
                    ->with(['program', 'tentor'])
                    ->limit(5);
            }]);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama_ruangan', 'like', "%{$search}%")
                    ->orWhereHas('cabang', function ($c) use ($search) {
                        $c->where('nama_cabang', 'like', "%{$search}%");
                    });
            });
        }

        if ($cabangFilter !== 'all' && !empty($cabangFilter)) {
            $query->where('cabang_id', (int) $cabangFilter);
        }

        if (in_array($statusFilter, ['aktif', 'nonaktif'], true)) {
            $query->where('status', $statusFilter);
        }

        $ruangans = $query->orderBy('cabang_id')->orderBy('nama_ruangan')->get();

        // Master cabang lists
        $allCabangs = Cabang::orderBy('nama_cabang')->get();
        $activeCabangs = Cabang::where('status', 'aktif')->orderBy('nama_cabang')->get();

        // High level overview metrics
        $totalRuangan = Ruangan::count();
        $ruanganAktif = Ruangan::where('status', 'aktif')->count();
        $ruanganNonaktif = Ruangan::where('status', 'nonaktif')->count();
        $cabangMetrics = Cabang::withCount('ruangans')->orderBy('nama_cabang')->get();

        // Selected room for the inspector panel
        $selectedRuanganId = $request->query('selected');
        $selectedRuangan = null;
        if ($selectedRuanganId) {
            $selectedRuangan = $ruangans->firstWhere('id', (int) $selectedRuanganId);
        }
        if (!$selectedRuangan && $ruangans->isNotEmpty()) {
            $selectedRuangan = $ruangans->first();
        }

        return view('superadmin.ruangan.index', [
            'user' => $user,
            'ruangans' => $ruangans,
            'selectedRuangan' => $selectedRuangan,
            'allCabangs' => $allCabangs,
            'activeCabangs' => $activeCabangs,
            'search' => $search,
            'cabangFilter' => $cabangFilter,
            'statusFilter' => $statusFilter,
            'totalRuangan' => $totalRuangan,
            'ruanganAktif' => $ruanganAktif,
            'ruanganNonaktif' => $ruanganNonaktif,
            'cabangMetrics' => $cabangMetrics,
        ]);
    }

    /**
     * Store a newly created room in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'cabang_id' => ['required', Rule::exists('cabang', 'id')],
            'nama_ruangan' => [
                'required',
                'string',
                'max:100',
                Rule::unique('ruangan', 'nama_ruangan')->where(function ($query) use ($request) {
                    return $query->where('cabang_id', $request->input('cabang_id'));
                }),
            ],
            'kapasitas' => 'nullable|integer|min:1|max:1000',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'cabang_id.required' => 'Cabang wajib dipilih.',
            'cabang_id.exists' => 'Cabang yang dipilih tidak valid.',
            'nama_ruangan.required' => 'Nama ruangan wajib diisi.',
            'nama_ruangan.max' => 'Nama ruangan maksimal 100 karakter.',
            'nama_ruangan.unique' => 'Nama ruangan ini sudah ada di cabang yang dipilih.',
            'kapasitas.integer' => 'Kapasitas harus berupa bilangan bulat.',
            'kapasitas.min' => 'Kapasitas minimal 1 orang.',
            'kapasitas.max' => 'Kapasitas maksimal 1000 orang.',
            'status.required' => 'Status ruangan wajib dipilih.',
            'status.in' => 'Status ruangan harus aktif atau nonaktif.',
        ]);

        $ruangan = Ruangan::create($validated);

        return redirect()
            ->route('superadmin.ruangan.index', ['selected' => $ruangan->id])
            ->with('success', "Ruangan '{$ruangan->nama_ruangan}' berhasil ditambahkan.");
    }

    /**
     * Update the specified room in storage.
     */
    public function update(Request $request, Ruangan $ruangan): RedirectResponse
    {
        $validated = $request->validate([
            'cabang_id' => ['required', Rule::exists('cabang', 'id')],
            'nama_ruangan' => [
                'required',
                'string',
                'max:100',
                Rule::unique('ruangan', 'nama_ruangan')->where(function ($query) use ($request) {
                    return $query->where('cabang_id', $request->input('cabang_id'));
                })->ignore($ruangan->id),
            ],
            'kapasitas' => 'nullable|integer|min:1|max:1000',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'cabang_id.required' => 'Cabang wajib dipilih.',
            'cabang_id.exists' => 'Cabang yang dipilih tidak valid.',
            'nama_ruangan.required' => 'Nama ruangan wajib diisi.',
            'nama_ruangan.max' => 'Nama ruangan maksimal 100 karakter.',
            'nama_ruangan.unique' => 'Nama ruangan ini sudah digunakan oleh ruangan lain di cabang yang sama.',
            'kapasitas.integer' => 'Kapasitas harus berupa bilangan bulat.',
            'kapasitas.min' => 'Kapasitas minimal 1 orang.',
            'kapasitas.max' => 'Kapasitas maksimal 1000 orang.',
            'status.required' => 'Status ruangan wajib dipilih.',
            'status.in' => 'Status ruangan harus aktif atau nonaktif.',
        ]);

        $ruangan->update($validated);

        return redirect()
            ->route('superadmin.ruangan.index', ['selected' => $ruangan->id])
            ->with('success', "Data ruangan '{$ruangan->nama_ruangan}' berhasil diperbarui.");
    }

    /**
     * Toggle active/inactive status of the room.
     */
    public function toggleStatus(Ruangan $ruangan): RedirectResponse
    {
        $newStatus = $ruangan->status === 'aktif' ? 'nonaktif' : 'aktif';
        $ruangan->update(['status' => $newStatus]);

        $label = $newStatus === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Ruangan '{$ruangan->nama_ruangan}' berhasil {$label}.");
    }

    /**
     * Remove the specified room from storage if no relation exists.
     */
    public function destroy(Ruangan $ruangan): RedirectResponse
    {
        if ($ruangan->jadwals()->exists()) {
            return back()->with('error', "Ruangan '{$ruangan->nama_ruangan}' tidak dapat dihapus permanen karena masih terikat dengan riwayat jadwal kelas. Silakan nonaktifkan status ruangan.");
        }

        $nama = $ruangan->nama_ruangan;
        $ruangan->delete();

        return redirect()
            ->route('superadmin.ruangan.index')
            ->with('success', "Ruangan '{$nama}' berhasil dihapus permanen.");
    }
}
