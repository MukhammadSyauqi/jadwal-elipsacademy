<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Jadwal;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SuperadminCabangController extends Controller
{
    /**
     * Display a listing of branches with metrics and inspector details.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $search = trim((string) $request->query('q', ''));
        $statusFilter = $request->query('status', 'all');

        $query = Cabang::query()
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
                    ->with(['program', 'tentor'])
                    ->limit(6);
            }]);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama_cabang', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        if (in_array($statusFilter, ['aktif', 'nonaktif'], true)) {
            $query->where('status', $statusFilter);
        }

        $cabangs = $query->orderBy('nama_cabang')->get();

        // High level overview metrics
        $totalCabang = Cabang::count();
        $cabangAktif = Cabang::where('status', 'aktif')->count();
        $cabangNonaktif = Cabang::where('status', 'nonaktif')->count();
        $totalJadwalAktif = Jadwal::where('status', '!=', 'dibatalkan')->count();

        // Selected branch for the right side inspector
        $selectedCabangId = $request->query('selected');
        $selectedCabang = null;
        if ($selectedCabangId) {
            $selectedCabang = $cabangs->firstWhere('id', (int) $selectedCabangId);
        }
        if (!$selectedCabang && $cabangs->isNotEmpty()) {
            $selectedCabang = $cabangs->first();
        }

        return view('superadmin.cabang.index', [
            'user' => $user,
            'cabangs' => $cabangs,
            'selectedCabang' => $selectedCabang,
            'search' => $search,
            'statusFilter' => $statusFilter,
            'totalCabang' => $totalCabang,
            'cabangAktif' => $cabangAktif,
            'cabangNonaktif' => $cabangNonaktif,
            'totalJadwalAktif' => $totalJadwalAktif,
        ]);
    }

    /**
     * Store a newly created branch in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_cabang' => 'required|string|max:100|unique:cabang,nama_cabang',
            'alamat' => 'nullable|string|max:1000',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'nama_cabang.required' => 'Nama cabang wajib diisi.',
            'nama_cabang.unique' => 'Nama cabang ini sudah terdaftar.',
            'status.in' => 'Status cabang harus aktif atau nonaktif.',
        ]);

        $cabang = Cabang::create($validated);

        return redirect()
            ->route('superadmin.cabang.index', ['selected' => $cabang->id])
            ->with('success', "Cabang '{$cabang->nama_cabang}' berhasil ditambahkan.");
    }

    /**
     * Update the specified branch in storage.
     */
    public function update(Request $request, Cabang $cabang): RedirectResponse
    {
        $validated = $request->validate([
            'nama_cabang' => [
                'required',
                'string',
                'max:100',
                Rule::unique('cabang', 'nama_cabang')->ignore($cabang->id),
            ],
            'alamat' => 'nullable|string|max:1000',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'nama_cabang.required' => 'Nama cabang wajib diisi.',
            'nama_cabang.unique' => 'Nama cabang ini sudah digunakan oleh cabang lain.',
            'status.in' => 'Status cabang harus aktif atau nonaktif.',
        ]);

        $cabang->update($validated);

        return redirect()
            ->route('superadmin.cabang.index', ['selected' => $cabang->id])
            ->with('success', "Data cabang '{$cabang->nama_cabang}' berhasil diperbarui.");
    }

    /**
     * Toggle active/inactive status of the branch.
     */
    public function toggleStatus(Cabang $cabang): RedirectResponse
    {
        $newStatus = $cabang->status === 'aktif' ? 'nonaktif' : 'aktif';
        $cabang->update(['status' => $newStatus]);

        $label = $newStatus === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Cabang '{$cabang->nama_cabang}' berhasil {$label}.");
    }

    /**
     * Remove the specified branch from storage if no relation exists.
     */
    public function destroy(Cabang $cabang): RedirectResponse
    {
        if ($cabang->jadwals()->exists()) {
            return back()->with('error', "Cabang '{$cabang->nama_cabang}' tidak dapat dihapus permanen karena sudah memiliki riwayat jadwal (PRD 14.2 Relational Guard). Silakan nonaktifkan status cabang.");
        }

        $nama = $cabang->nama_cabang;
        $cabang->delete();

        return redirect()
            ->route('superadmin.cabang.index')
            ->with('success', "Cabang '{$nama}' berhasil dihapus permanen.");
    }
}
