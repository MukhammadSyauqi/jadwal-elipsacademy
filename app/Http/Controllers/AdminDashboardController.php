<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Jadwal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // 1. Cabang handling
        $cabangs = Cabang::where('status', 'aktif')->orderBy('nama_cabang')->get();
        $selectedCabangId = $request->query('cabang_id');

        if (!$selectedCabangId) {
            // Heuristic: check if user's name matches a cabang name
            if ($user && stripos($user->nama, 'Candi') !== false) {
                $candi = $cabangs->firstWhere('nama_cabang', 'Candi');
                $selectedCabangId = $candi ? $candi->id : ($cabangs->first()?->id ?? 1);
            } else {
                // Default to first branch (e.g. Buduran or id 1)
                $selectedCabangId = $cabangs->first()?->id ?? 1;
            }
        }

        $selectedCabang = $cabangs->firstWhere('id', $selectedCabangId) ?? $cabangs->first();

        // 2. Date handling
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();
        $todayDate = $today->toDateString();
        $tomorrowDate = $tomorrow->toDateString();

        $selectedDate = $request->query('tanggal', $todayDate);
        try {
            $dateObj = Carbon::parse($selectedDate)->locale('id');
        } catch (\Exception $e) {
            $dateObj = $today->copy()->locale('id');
            $selectedDate = $todayDate;
        }

        $formattedDate = $dateObj->isoFormat('dddd, D MMMM YYYY');
        $isToday = ($selectedDate === $todayDate);
        $isTomorrow = ($selectedDate === $tomorrowDate);

        // 3. Base schedules for the selected date & branch (used for summary stats and session badge counters)
        $baseJadwalQuery = Jadwal::with(['cabang', 'program', 'tentor'])
            ->when($selectedCabang, fn($q) => $q->where('cabang_id', $selectedCabang->id))
            ->where('tanggal', $selectedDate);

        $allDayJadwal = (clone $baseJadwalQuery)->orderBy('jam_mulai')->get();

        // Calculate summary cards
        $totalSchedules = $allDayJadwal->count();
        $ongoingSchedules = $allDayJadwal->filter(fn($j) => $j->display_status === 'sedang_berlangsung')->count();
        $completedSchedules = $allDayJadwal->where('status', 'selesai')->count();
        $cancelledSchedules = $allDayJadwal->where('status', 'dibatalkan')->count();

        $summary = [
            'total' => $totalSchedules,
            'ongoing' => $ongoingSchedules,
            'completed' => $completedSchedules,
            'cancelled' => $cancelledSchedules,
        ];

        // Calculate counts per session
        $sessionCounts = [
            'semua' => $totalSchedules,
            'pagi' => $allDayJadwal->filter(fn($j) => $j->sesi === 'pagi')->count(),
            'siang' => $allDayJadwal->filter(fn($j) => $j->sesi === 'siang')->count(),
            'sore' => $allDayJadwal->filter(fn($j) => $j->sesi === 'sore')->count(),
            'malam' => $allDayJadwal->filter(fn($j) => $j->sesi === 'malam')->count(),
        ];

        // 4. Apply Filters (Session & Search) to schedule list
        $sesi = strtolower(trim($request->query('sesi', 'semua')));
        $search = trim($request->query('q', ''));

        $query = clone $baseJadwalQuery;

        if ($sesi && $sesi !== 'semua') {
            if ($sesi === 'pagi') {
                $query->whereTime('jam_mulai', '<', '12:00:00');
            } elseif ($sesi === 'siang') {
                $query->whereTime('jam_mulai', '>=', '12:00:00')->whereTime('jam_mulai', '<', '15:30:00');
            } elseif ($sesi === 'sore') {
                $query->whereTime('jam_mulai', '>=', '15:30:00')->whereTime('jam_mulai', '<', '18:30:00');
            } elseif ($sesi === 'malam') {
                $query->whereTime('jam_mulai', '>=', '18:30:00');
            }
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_kelas', 'like', "%{$search}%")
                    ->orWhere('ruangan', 'like', "%{$search}%")
                    ->orWhereHas('tentor', fn($t) => $t->where('nama', 'like', "%{$search}%"))
                    ->orWhereHas('program', fn($p) => $p->where('nama_program', 'like', "%{$search}%"));
            });
        }

        $jadwalList = $query->orderBy('jam_mulai', 'asc')->get();

        return view('admin.dashboard', [
            'user' => $user,
            'cabangs' => $cabangs,
            'selectedCabang' => $selectedCabang,
            'selectedDate' => $selectedDate,
            'formattedDate' => $formattedDate,
            'isToday' => $isToday,
            'isTomorrow' => $isTomorrow,
            'todayDate' => $todayDate,
            'tomorrowDate' => $tomorrowDate,
            'summary' => $summary,
            'sessionCounts' => $sessionCounts,
            'sesi' => $sesi,
            'q' => $search,
            'jadwalList' => $jadwalList,
        ]);
    }
}
