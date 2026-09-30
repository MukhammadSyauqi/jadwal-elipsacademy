<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ExcelExportTrait;
use App\Models\Cabang;
use App\Models\Jadwal;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SuperadminRekapController extends Controller
{
    use ExcelExportTrait;

    protected array $jenisKelasList = ['Private', 'Rombel', 'Business'];
    protected array $statusList = ['terjadwal', 'selesai', 'dibatalkan'];

    /**
     * Display schedule recapitulation for superadmin with customizable date range and filters.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $filters = $this->extractFilters($request);
        $cabangs = Cabang::orderBy('nama_cabang')->get();

        $jadwals = $this->queryJadwals($filters);

        $summary = [
            'total' => $jadwals->count(),
            'selesai' => $jadwals->where('status', 'selesai')->count(),
            'terjadwal' => $jadwals->where('status', 'terjadwal')->count(),
            'dibatalkan' => $jadwals->where('status', 'dibatalkan')->count(),
            'tentor_count' => $jadwals->pluck('tentor_id')->filter()->unique()->count(),
        ];

        return view('superadmin.rekap.index', [
            'user' => $user,
            'filters' => $filters,
            'cabangs' => $cabangs,
            'jenisKelasList' => $this->jenisKelasList,
            'statusList' => $this->statusList,
            'jadwals' => $jadwals,
            'summary' => $summary,
        ]);
    }

    /**
     * Export recapitulation report to Excel (.xlsx).
     */
    public function exportExcel(Request $request): StreamedResponse
    {
        $filters = $this->extractFilters($request);
        $jadwals = $this->queryJadwals($filters);

        $cabangText = 'Semua Cabang';
        if ($filters['cabang_id'] !== 'semua' && !empty($filters['cabang_id'])) {
            $cabang = Cabang::find($filters['cabang_id']);
            if ($cabang) {
                $cabangText = $cabang->nama_cabang;
            }
        }

        $statusText = ($filters['status'] === 'semua') ? 'Semua Status' : ucfirst($filters['status']);
        $jenisText = ($filters['jenis_kelas'] === 'semua') ? 'Semua Jenis' : $filters['jenis_kelas'];

        $tglMulai = Carbon::parse($filters['tanggal_mulai'])->format('d/m/Y');
        $tglSelesai = Carbon::parse($filters['tanggal_selesai'])->format('d/m/Y');
        $periodeText = $tglMulai . ' - ' . $tglSelesai;

        $metadata = [
            'Rentang Tanggal' => $periodeText,
            'Cabang' => $cabangText,
            'Status' => $statusText,
            'Jenis Kelas' => $jenisText,
            'Total Jadwal' => $jadwals->count() . ' Jadwal',
        ];

        $safeCabang = preg_replace('/[^A-Za-z0-9_-]/', '_', $cabangText);
        $filename = 'Rekap_Jadwal_' . $safeCabang . '_' . Carbon::parse($filters['tanggal_mulai'])->format('Ymd') . '_' . Carbon::parse($filters['tanggal_selesai'])->format('Ymd') . '.xlsx';

        return $this->buildExcel(
            $jadwals,
            $filename,
            'Rekapitulasi Jadwal - ' . $cabangText,
            $metadata
        );
    }

    /**
     * Export recapitulation report to PDF (.pdf).
     */
    public function exportPdf(Request $request): Response
    {
        $filters = $this->extractFilters($request);
        $jadwals = $this->queryJadwals($filters);

        $cabangText = 'Semua Cabang';
        if ($filters['cabang_id'] !== 'semua' && !empty($filters['cabang_id'])) {
            $cabang = Cabang::find($filters['cabang_id']);
            if ($cabang) {
                $cabangText = $cabang->nama_cabang;
            }
        }

        $statusText = ($filters['status'] === 'semua') ? 'Semua Status' : ucfirst($filters['status']);
        $jenisText = ($filters['jenis_kelas'] === 'semua') ? 'Semua Jenis' : $filters['jenis_kelas'];

        $tglMulai = Carbon::parse($filters['tanggal_mulai'])->format('d/m/Y');
        $tglSelesai = Carbon::parse($filters['tanggal_selesai'])->format('d/m/Y');
        $periodeText = $tglMulai . ' - ' . $tglSelesai;

        $summary = [
            'total' => $jadwals->count(),
            'selesai' => $jadwals->where('status', 'selesai')->count(),
            'terjadwal' => $jadwals->where('status', 'terjadwal')->count(),
            'dibatalkan' => $jadwals->where('status', 'dibatalkan')->count(),
        ];

        $printedAt = Carbon::now()->locale('id')->isoFormat('DD/MM/YYYY HH:mm') . ' WIB';

        $pdf = Pdf::loadView('superadmin.rekap.pdf', [
            'title' => 'Rekapitulasi Jadwal Kelas - ' . $cabangText,
            'periodeText' => $periodeText,
            'cabangText' => $cabangText,
            'statusText' => $statusText,
            'jenisText' => $jenisText,
            'jadwals' => $jadwals,
            'summary' => $summary,
            'printedAt' => $printedAt,
        ]);

        $pdf->setPaper('a4', 'landscape');

        $safeCabang = preg_replace('/[^A-Za-z0-9_-]/', '_', $cabangText);
        $filename = 'Rekap_Jadwal_' . $safeCabang . '_' . Carbon::parse($filters['tanggal_mulai'])->format('Ymd') . '_' . Carbon::parse($filters['tanggal_selesai'])->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Extract and normalize filters from request.
     */
    private function extractFilters(Request $request): array
    {
        $now = now();
        $defaultMulai = $now->copy()->startOfMonth()->format('Y-m-d');
        $defaultSelesai = $now->copy()->format('Y-m-d');

        $tglMulai = $request->input('tanggal_mulai', $defaultMulai);
        $tglSelesai = $request->input('tanggal_selesai', $defaultSelesai);

        // Sanitize valid date strings
        try {
            Carbon::parse($tglMulai);
        } catch (\Exception $e) {
            $tglMulai = $defaultMulai;
        }

        try {
            Carbon::parse($tglSelesai);
        } catch (\Exception $e) {
            $tglSelesai = $defaultSelesai;
        }

        // Ensure tglMulai <= tglSelesai
        if ($tglMulai > $tglSelesai) {
            $temp = $tglMulai;
            $tglMulai = $tglSelesai;
            $tglSelesai = $temp;
        }

        return [
            'tanggal_mulai' => $tglMulai,
            'tanggal_selesai' => $tglSelesai,
            'cabang_id' => $request->input('cabang_id', 'semua'),
            'status' => $request->input('status', 'semua'),
            'jenis_kelas' => $request->input('jenis_kelas', 'semua'),
        ];
    }

    /**
     * Query jadwals based on given filters.
     */
    private function queryJadwals(array $filters)
    {
        $query = Jadwal::with(['cabang', 'program', 'tentor', 'ruanganRef'])
            ->whereBetween('tanggal', [$filters['tanggal_mulai'], $filters['tanggal_selesai']]);

        if ($filters['cabang_id'] !== 'semua' && !empty($filters['cabang_id'])) {
            $query->where('cabang_id', $filters['cabang_id']);
        }

        if ($filters['status'] !== 'semua' && !empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if ($filters['jenis_kelas'] !== 'semua' && !empty($filters['jenis_kelas'])) {
            $query->where('jenis_kelas', $filters['jenis_kelas']);
        }

        return $query->orderBy('tanggal', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->get();
    }
}
