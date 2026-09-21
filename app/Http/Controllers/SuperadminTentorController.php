<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Tentor;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuperadminTentorController extends Controller
{
    /**
     * Display a listing of instructors with teaching load and today's classes.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $today = Carbon::today()->toDateString();
        $search = trim((string) $request->query('q', ''));
        $statusFilter = $request->query('status', 'all');

        $query = Tentor::query()
            ->withCount([
                'jadwals as total_jadwal_count',
                'jadwals as jadwal_aktif_count' => function ($q) {
                    $q->where('status', '!=', 'dibatalkan');
                },
                'jadwals as jadwal_hari_ini_count' => function ($q) use ($today) {
                    $q->whereDate('tanggal', $today)
                        ->where('status', '!=', 'dibatalkan');
                },
            ])
            ->with([
                'jadwals' => function ($q) use ($today) {
                    $q->where('status', '!=', 'dibatalkan')
                        ->whereDate('tanggal', '>=', $today)
                        ->orderBy('tanggal')
                        ->orderBy('jam_mulai')
                        ->with(['program', 'cabang'])
                        ->limit(6);
                },
            ]);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('no_hp', 'like', "%{$search}%")
                    ->orWhere('keahlian', 'like', "%{$search}%");
            });
        }

        if (in_array($statusFilter, ['aktif', 'nonaktif'], true)) {
            $query->where('status', $statusFilter);
        }

        $tentors = $query->orderBy('nama')->get();

        // Metrics
        $totalTentor = Tentor::count();
        $tentorAktif = Tentor::where('status', 'aktif')->count();
        $tentorNonaktif = Tentor::where('status', 'nonaktif')->count();
        
        $tentorMengajarHariIni = Jadwal::whereDate('tanggal', $today)
            ->where('status', '!=', 'dibatalkan')
            ->distinct()
            ->count('tentor_id');

        $totalSesiHariIni = Jadwal::whereDate('tanggal', $today)
            ->where('status', '!=', 'dibatalkan')
            ->count();

        // Selected tentor for inspector
        $selectedTentorId = $request->query('selected');
        $selectedTentor = null;
        if ($selectedTentorId) {
            $selectedTentor = $tentors->firstWhere('id', (int) $selectedTentorId);
        }
        if (!$selectedTentor && $tentors->isNotEmpty()) {
            $selectedTentor = $tentors->first();
        }

        // Additional load info for selected tentor
        $selectedTentorTodayClasses = collect();
        if ($selectedTentor) {
            $selectedTentorTodayClasses = Jadwal::where('tentor_id', $selectedTentor->id)
                ->whereDate('tanggal', $today)
                ->where('status', '!=', 'dibatalkan')
                ->orderBy('jam_mulai')
                ->with(['program', 'cabang'])
                ->get();
        }

        return view('superadmin.tentor.index', [
            'user' => $user,
            'tentors' => $tentors,
            'selectedTentor' => $selectedTentor,
            'selectedTentorTodayClasses' => $selectedTentorTodayClasses,
            'search' => $search,
            'statusFilter' => $statusFilter,
            'totalTentor' => $totalTentor,
            'tentorAktif' => $tentorAktif,
            'tentorNonaktif' => $tentorNonaktif,
            'tentorMengajarHariIni' => $tentorMengajarHariIni,
            'totalSesiHariIni' => $totalSesiHariIni,
            'todayFormatted' => Carbon::today()->translatedFormat('l, d F Y'),
        ]);
    }

    /**
     * Store a newly created instructor in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:150',
            'no_hp' => 'nullable|string|max:20',
            'keahlian' => 'nullable|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'nama.required' => 'Nama tentor pengajar wajib diisi.',
            'status.in' => 'Status tentor harus aktif atau nonaktif.',
        ]);

        $tentor = Tentor::create($validated);

        return redirect()
            ->route('superadmin.tentor.index', ['selected' => $tentor->id])
            ->with('success', "Tentor pengajar '{$tentor->nama}' berhasil ditambahkan.");
    }

    /**
     * Update the specified instructor in storage.
     */
    public function update(Request $request, Tentor $tentor): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:150',
            'no_hp' => 'nullable|string|max:20',
            'keahlian' => 'nullable|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'nama.required' => 'Nama tentor pengajar wajib diisi.',
            'status.in' => 'Status tentor harus aktif atau nonaktif.',
        ]);

        $tentor->update($validated);

        return redirect()
            ->route('superadmin.tentor.index', ['selected' => $tentor->id])
            ->with('success', "Data tentor '{$tentor->nama}' berhasil diperbarui.");
    }

    /**
     * Toggle active/inactive status of the instructor.
     */
    public function toggleStatus(Tentor $tentor): RedirectResponse
    {
        $newStatus = $tentor->status === 'aktif' ? 'nonaktif' : 'aktif';
        $tentor->update(['status' => $newStatus]);

        $label = $newStatus === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Tentor '{$tentor->nama}' berhasil {$label}.");
    }

    /**
     * Remove the specified instructor from storage if no relation exists.
     */
    public function destroy(Tentor $tentor): RedirectResponse
    {
        if ($tentor->jadwals()->exists()) {
            return back()->with('error', "Tentor '{$tentor->nama}' tidak dapat dihapus permanen karena sudah memiliki riwayat jadwal mengajar (PRD 14.2 Relational Guard). Silakan nonaktifkan status tentor.");
        }

        $nama = $tentor->nama;
        $tentor->delete();

        return redirect()
            ->route('superadmin.tentor.index')
            ->with('success', "Tentor '{$nama}' berhasil dihapus permanen.");
    }
}
