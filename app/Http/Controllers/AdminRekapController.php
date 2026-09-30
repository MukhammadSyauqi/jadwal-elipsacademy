<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ExcelExportTrait;
use App\Models\Cabang;
use App\Models\Jadwal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminRekapController extends Controller
{
    use ExcelExportTrait;

    protected array $months = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    /**
     * Display monthly schedule summary for admin's branch.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $now = now();

        $bulan = (int) $request->input('bulan', $now->month);
        $tahun = (int) $request->input('tahun', $now->year);

        if ($bulan < 1 || $bulan > 12) {
            $bulan = (int) $now->month;
        }
        if ($tahun < 2020 || $tahun > 2040) {
            $tahun = (int) $now->year;
        }

        [$startDate, $endDate, $isCurrentMonth] = $this->calculateDateRange($bulan, $tahun, $now);

        $query = Jadwal::with(['cabang', 'program', 'tentor', 'ruanganRef'])
            ->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);

        if ($user && $user->cabang_id !== null) {
            $query->where('cabang_id', $user->cabang_id);
            $currentCabang = Cabang::find($user->cabang_id);
        } else {
            $currentCabang = null;
        }

        $jadwals = $query->orderBy('tanggal', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->get();

        $summary = [
            'total' => $jadwals->count(),
            'selesai' => $jadwals->where('status', 'selesai')->count(),
            'terjadwal' => $jadwals->where('status', 'terjadwal')->count(),
            'dibatalkan' => $jadwals->where('status', 'dibatalkan')->count(),
            'tentor_count' => $jadwals->pluck('tentor_id')->filter()->unique()->count(),
        ];

        $years = range($now->year - 3, $now->year + 1);

        return view('admin.rekap.index', [
            'user' => $user,
            'currentCabang' => $currentCabang,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'months' => $this->months,
            'years' => $years,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'isCurrentMonth' => $isCurrentMonth,
            'jadwals' => $jadwals,
            'summary' => $summary,
        ]);
    }

    /**
     * Export monthly schedule report to Excel (.xlsx).
     */
    public function exportExcel(Request $request): StreamedResponse
    {
        $user = $request->user();
        $now = now();

        $bulan = (int) $request->input('bulan', $now->month);
        $tahun = (int) $request->input('tahun', $now->year);

        if ($bulan < 1 || $bulan > 12) {
            $bulan = (int) $now->month;
        }
        if ($tahun < 2020 || $tahun > 2040) {
            $tahun = (int) $now->year;
        }

        [$startDate, $endDate, $isCurrentMonth] = $this->calculateDateRange($bulan, $tahun, $now);

        $query = Jadwal::with(['cabang', 'program', 'tentor', 'ruanganRef'])
            ->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);

        $cabangNama = 'Semua Cabang';
        if ($user && $user->cabang_id !== null) {
            $query->where('cabang_id', $user->cabang_id);
            $cabang = Cabang::find($user->cabang_id);
            if ($cabang) {
                $cabangNama = $cabang->nama_cabang;
            }
        }

        $jadwals = $query->orderBy('tanggal', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->get();

        $namaBulan = $this->months[$bulan] ?? 'Bulan ' . $bulan;
        $periodeText = $namaBulan . ' ' . $tahun . ' (' . $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y') . ')';

        $metadata = [
            'Periode' => $periodeText,
            'Cabang' => $cabangNama,
            'Total Jadwal' => $jadwals->count() . ' Jadwal',
        ];

        $safeCabang = preg_replace('/[^A-Za-z0-9_-]/', '_', $cabangNama);
        $filename = 'Rekap_Jadwal_' . $safeCabang . '_' . $namaBulan . '_' . $tahun . '.xlsx';

        return $this->buildExcel(
            $jadwals,
            $filename,
            'Rekapitulasi Jadwal Bulanan - ' . $cabangNama,
            $metadata
        );
    }

    /**
     * Calculate start date and end date based on month/year and current month rule.
     */
    private function calculateDateRange(int $bulan, int $tahun, Carbon $now): array
    {
        $startDate = Carbon::create($tahun, $bulan, 1)->startOfDay();

        // If current month & year: end date is today (bulan berjalan)
        if ($tahun === (int) $now->year && $bulan === (int) $now->month) {
            $endDate = $now->copy()->endOfDay();
            $isCurrentMonth = true;
        } else {
            $endDate = Carbon::create($tahun, $bulan, 1)->endOfMonth()->endOfDay();
            $isCurrentMonth = false;
        }

        return [$startDate, $endDate, $isCurrentMonth];
    }
}
