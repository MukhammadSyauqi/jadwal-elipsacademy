<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Jadwal;
use App\Models\Program;
use App\Models\Tentor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuperadminJadwalController extends Controller
{
    /**
     * Display the comprehensive cross-branch schedule management table.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $today = Carbon::today()->toDateString();
        $tomorrow = Carbon::tomorrow()->toDateString();
        $startOfWeek = Carbon::today()->startOfWeek()->toDateString();
        $endOfWeek = Carbon::today()->endOfWeek()->toDateString();

        // 1. Top Summary Metrics
        $totalJadwalHariIni = Jadwal::whereDate('tanggal', $today)->count();
        $selesaiHariIni = Jadwal::whereDate('tanggal', $today)->where('status', 'selesai')->count();
        $cabangs = Cabang::where('status', 'aktif')->orderBy('nama_cabang')->get();
        $programs = Program::where('status', 'aktif')->orderBy('nama_program')->get();
        $tentors = Tentor::where('status', 'aktif')->orderBy('nama')->get();

        $tentorMengajarCount = Jadwal::whereDate('tanggal', $today)
            ->where('status', '!=', 'dibatalkan')
            ->distinct('tentor_id')
            ->count('tentor_id');

        // 2. Query Building with Filters
        $query = Jadwal::with(['cabang', 'program', 'tentor']);

        // Search filter
        $q = $request->query('q');
        if (!empty($q)) {
            $query->where(function ($sub) use ($q) {
                $sub->where('nama_kelas', 'like', "%{$q}%")
                    ->orWhere('ruangan', 'like', "%{$q}%")
                    ->orWhere('catatan', 'like', "%{$q}%")
                    ->orWhereHas('program', fn($p) => $p->where('nama_program', 'like', "%{$q}%"))
                    ->orWhereHas('tentor', fn($t) => $t->where('nama', 'like', "%{$q}%"))
                    ->orWhereHas('cabang', fn($c) => $c->where('nama_cabang', 'like', "%{$q}%"));
            });
        }

        // Cabang filter
        $cabangId = $request->query('cabang_id');
        if (!empty($cabangId) && $cabangId !== 'all') {
            $query->where('cabang_id', $cabangId);
        }

        // Jenis Kelas filter
        $jenisKelas = $request->query('jenis_kelas');
        if (!empty($jenisKelas) && $jenisKelas !== 'all') {
            $query->where('jenis_kelas', strtolower($jenisKelas));
        }

        // Status filter
        $status = $request->query('status');
        if (!empty($status) && $status !== 'all') {
            $query->where('status', strtolower($status));
        }

        // Date Range Horizon filter
        $range = $request->query('range', 'semua');
        $customTanggal = $request->query('tanggal');

        if (!empty($customTanggal)) {
            $query->whereDate('tanggal', $customTanggal);
            $range = 'custom';
        } elseif ($range === 'hari_ini') {
            $query->whereDate('tanggal', $today);
        } elseif ($range === 'besok') {
            $query->whereDate('tanggal', $tomorrow);
        } elseif ($range === 'minggu_ini') {
            $query->whereBetween('tanggal', [$startOfWeek, $endOfWeek]);
        }

        // Sort by date descending and start time ascending
        $query->orderBy('tanggal', 'desc')->orderBy('jam_mulai', 'asc');

        // Paginate results preserving query string
        $perPage = (int) $request->query('per_page', 15);
        $jadwals = $query->paginate($perPage)->withQueryString();

        return view('superadmin.jadwal.index', [
            'user' => $user,
            'jadwals' => $jadwals,
            'cabangs' => $cabangs,
            'programs' => $programs,
            'tentors' => $tentors,
            'totalJadwalHariIni' => $totalJadwalHariIni,
            'selesaiHariIni' => $selesaiHariIni,
            'tentorMengajarCount' => $tentorMengajarCount,
            'totalTentorsCount' => $tentors->count(),
            // Current filter values
            'selectedCabangId' => $cabangId ?? 'all',
            'selectedJenisKelas' => $jenisKelas ?? 'all',
            'selectedStatus' => $status ?? 'all',
            'selectedRange' => $range,
            'selectedTanggal' => $customTanggal ?? '',
            'searchQuery' => $q ?? '',
            'todayDate' => $today,
        ]);
    }
}
