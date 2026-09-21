<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Jadwal;
use App\Models\Program;
use App\Models\Tentor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuperadminDashboardController extends Controller
{
    /**
     * Display the comprehensive superadmin dashboard.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $today = Carbon::today()->toDateString();
        $nowTime = Carbon::now()->format('H:i:s');
        $startOfWeek = Carbon::today()->startOfWeek()->toDateString();
        $endOfWeek = Carbon::today()->endOfWeek()->toDateString();

        // 1. Bento Grid Metrics
        $totalSesiMingguIni = Jadwal::whereBetween('tanggal', [$startOfWeek, $endOfWeek])->count();
        
        // Active rooms used today
        $roomsUsedToday = Jadwal::whereDate('tanggal', $today)
            ->where('status', '!=', 'dibatalkan')
            ->distinct()
            ->pluck('ruangan')
            ->filter()
            ->values();

        // Total known rooms across standard branches
        $standardRooms = ['Ruang 1', 'Ruang 2', 'Lab Komputer A', 'Lab Komputer B', 'Studio Desain', 'Lab Multimedia Candi', 'Lab IT Candi', 'Ruang A Candi'];
        $allRooms = array_unique(array_merge($standardRooms, $roomsUsedToday->toArray()));
        $totalRoomsCount = max(count($allRooms), 6);
        $usedRoomsCount = $roomsUsedToday->count();
        $okupansiPersen = round(($usedRoomsCount / $totalRoomsCount) * 100, 1);

        // Active tentors today
        $tentorAktifHariIni = Jadwal::whereDate('tanggal', $today)
            ->where('status', '!=', 'dibatalkan')
            ->distinct()
            ->count('tentor_id');
        $totalTentor = Tentor::where('status', 'aktif')->count();

        // Schedule integrity check: check for any overlapping sessions
        $conflictsCount = 0;
        $activeSchedulesToday = Jadwal::whereDate('tanggal', $today)
            ->where('status', '!=', 'dibatalkan')
            ->get();

        for ($i = 0; $i < $activeSchedulesToday->count(); $i++) {
            for ($j = $i + 1; $j < $activeSchedulesToday->count(); $j++) {
                $a = $activeSchedulesToday[$i];
                $b = $activeSchedulesToday[$j];
                $timeOverlap = ($a->jam_mulai < $b->jam_selesai) && ($a->jam_selesai > $b->jam_mulai);
                if ($timeOverlap) {
                    if ($a->tentor_id == $b->tentor_id || ($a->cabang_id == $b->cabang_id && $a->ruangan == $b->ruangan)) {
                        $conflictsCount++;
                    }
                }
            }
        }

        // 2. Today's Schedules with Filters
        $sesi = $request->query('sesi', 'all');
        $q = $request->query('q', '');

        $query = Jadwal::with(['cabang', 'program', 'tentor'])
            ->whereDate('tanggal', $today);

        if ($sesi === 'pagi') {
            $query->where('jam_mulai', '>=', '07:00:00')->where('jam_mulai', '<', '11:30:00');
        } elseif ($sesi === 'siang') {
            $query->where('jam_mulai', '>=', '11:30:00')->where('jam_mulai', '<', '15:30:00');
        } elseif ($sesi === 'sore') {
            $query->where('jam_mulai', '>=', '15:30:00');
        }

        if (!empty($q)) {
            $query->where(function ($sub) use ($q) {
                $sub->where('nama_kelas', 'like', "%{$q}%")
                    ->orWhere('ruangan', 'like', "%{$q}%")
                    ->orWhereHas('tentor', fn($t) => $t->where('nama', 'like', "%{$q}%"))
                    ->orWhereHas('program', fn($p) => $p->where('nama_program', 'like', "%{$q}%"))
                    ->orWhereHas('cabang', fn($c) => $c->where('nama_cabang', 'like', "%{$q}%"));
            });
        }

        $todaySchedules = $query->orderBy('jam_mulai', 'asc')->get();

        // 3. Live active session (running right now)
        $activeLiveSchedule = null;
        if ($sesi === 'all' && empty($q)) {
            $activeLiveSchedule = Jadwal::with(['cabang', 'program', 'tentor'])
                ->whereDate('tanggal', $today)
                ->where('status', '!=', 'dibatalkan')
                ->where('jam_mulai', '<=', $nowTime)
                ->where('jam_selesai', '>=', $nowTime)
                ->first();

            // Fallback to the next upcoming or first scheduled if none is currently live
            if (!$activeLiveSchedule) {
                $activeLiveSchedule = Jadwal::with(['cabang', 'program', 'tentor'])
                    ->whereDate('tanggal', $today)
                    ->where('status', 'terjadwal')
                    ->where('jam_mulai', '>=', $nowTime)
                    ->orderBy('jam_mulai', 'asc')
                    ->first() ?? $todaySchedules->first();
            }
        } elseif ($todaySchedules->isNotEmpty()) {
            // When filtered, only highlight if there's an ongoing session in the filtered results
            $activeLiveSchedule = $todaySchedules->first(function ($s) use ($nowTime) {
                return $s->status !== 'dibatalkan' && $s->jam_mulai <= $nowTime && $s->jam_selesai >= $nowTime;
            });
        }

        // 4. Room availability breakdown (per cabang / floor)
        $cabangs = Cabang::where('status', 'aktif')->orderBy('nama_cabang')->get();
        $roomAvailabilityByCabang = [];

        foreach ($cabangs as $cabang) {
            // Find distinct rooms for this branch
            $branchRooms = Jadwal::where('cabang_id', $cabang->id)
                ->distinct()
                ->pluck('ruangan')
                ->filter()
                ->values()
                ->toArray();

            if (empty($branchRooms)) {
                $branchRooms = ['Ruang 1', 'Ruang 2', 'Lab Komputer A'];
            }

            $roomStatuses = [];
            foreach ($branchRooms as $room) {
                $currentOccupant = Jadwal::with(['program', 'tentor'])
                    ->whereDate('tanggal', $today)
                    ->where('cabang_id', $cabang->id)
                    ->where('ruangan', $room)
                    ->where('status', '!=', 'dibatalkan')
                    ->where('jam_mulai', '<=', $nowTime)
                    ->where('jam_selesai', '>=', $nowTime)
                    ->first();

                $nextOccupant = Jadwal::with(['program', 'tentor'])
                    ->whereDate('tanggal', $today)
                    ->where('cabang_id', $cabang->id)
                    ->where('ruangan', $room)
                    ->where('status', 'terjadwal')
                    ->where('jam_mulai', '>', $nowTime)
                    ->orderBy('jam_mulai', 'asc')
                    ->first();

                $status = 'kosong';
                $statusText = 'Kosong';
                $detail = 'Siap Digunakan';

                if ($currentOccupant) {
                    $status = 'terpakai';
                    $statusText = 'Terpakai';
                    $detail = $currentOccupant->nama_kelas . ' (s/d ' . substr($currentOccupant->jam_selesai, 0, 5) . ')';
                } elseif ($nextOccupant) {
                    $status = 'segera';
                    $statusText = 'Segera';
                    $detail = 'Sesi ' . substr($nextOccupant->jam_mulai, 0, 5) . ' WIB';
                }

                $roomStatuses[] = [
                    'nama_ruang' => $room,
                    'status' => $status,
                    'status_text' => $statusText,
                    'detail' => $detail,
                ];
            }

            $roomAvailabilityByCabang[$cabang->nama_cabang] = $roomStatuses;
        }

        // 5. Recent schedule changes (last 5 updated_at)
        $perubahanTerakhir = Jadwal::with(['cabang', 'program', 'tentor'])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('superadmin.dashboard', [
            'user' => $user,
            'today' => $today,
            'totalSesiMingguIni' => $totalSesiMingguIni,
            'okupansiPersen' => $okupansiPersen,
            'usedRoomsCount' => $usedRoomsCount,
            'totalRoomsCount' => $totalRoomsCount,
            'tentorAktifHariIni' => $tentorAktifHariIni,
            'totalTentor' => $totalTentor,
            'conflictsCount' => $conflictsCount,
            'todaySchedules' => $todaySchedules,
            'activeLiveSchedule' => $activeLiveSchedule,
            'roomAvailabilityByCabang' => $roomAvailabilityByCabang,
            'perubahanTerakhir' => $perubahanTerakhir,
            'filterSesi' => $sesi,
            'searchQuery' => $q,
        ]);
    }
}
