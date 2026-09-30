<?php

namespace App\Http\Controllers\Traits;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait ExcelExportTrait
{
    /**
     * Build and stream an Excel (.xlsx) file for the given schedules.
     */
    protected function buildExcel(
        Collection $jadwals,
        string $filename,
        string $title = 'Rekapitulasi Jadwal Kelas',
        array $metadata = []
    ): StreamedResponse {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap Jadwal');

        // Page setup
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

        // 1. Title Banner
        $sheet->setCellValue('A1', 'ELIPS ACADEMY - ' . strtoupper($title));
        $sheet->mergeCells('A1:N1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('904D00'));
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // 2. Metadata / Period Info
        $metaRow = 2;
        if (!empty($metadata)) {
            $metaTexts = [];
            foreach ($metadata as $key => $val) {
                $metaTexts[] = $key . ': ' . $val;
            }
            $sheet->setCellValue('A2', implode('   |   ', $metaTexts));
            $sheet->mergeCells('A2:N2');
            $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('6E6E73'));
            $metaRow = 3;
        }

        // Table Header starts at $headerRow
        $headerRow = $metaRow + 1;
        $headers = [
            'A' => 'No',
            'B' => 'Tanggal',
            'C' => 'Hari',
            'D' => 'Jam Mulai',
            'E' => 'Jam Selesai',
            'F' => 'Program',
            'G' => 'Jenis Kelas',
            'H' => 'Mode Kelas',
            'I' => 'Tentor',
            'J' => 'Ruangan',
            'K' => 'Cabang',
            'L' => 'Pertemuan',
            'M' => 'Status',
            'N' => 'Catatan',
        ];

        foreach ($headers as $col => $headerText) {
            $cell = $col . $headerRow;
            $sheet->setCellValue($cell, $headerText);
        }

        // Header style: Brand orange background (#F28E2B), white bold text
        $headerRange = 'A' . $headerRow . ':N' . $headerRow;
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 10,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F28E2B'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D0741A'],
                ],
            ],
        ]);
        $sheet->getRowDimension($headerRow)->setRowHeight(26);

        // 3. Data Rows
        $currentRow = $headerRow + 1;
        $no = 1;

        $totalSelesai = 0;
        $totalTerjadwal = 0;
        $totalDibatalkan = 0;

        foreach ($jadwals as $j) {
            $tanggal = $j->tanggal ? Carbon::parse($j->tanggal) : null;
            $hari = $tanggal ? $tanggal->locale('id')->isoFormat('dddd') : '-';
            $tglStr = $tanggal ? $tanggal->format('d/m/Y') : '-';

            $jamMulai = $j->jam_mulai ? substr($j->jam_mulai, 0, 5) : '-';
            $jamSelesai = $j->jam_selesai ? substr($j->jam_selesai, 0, 5) : '-';

            $program = $j->program->nama_program ?? '-';
            $jenisKelas = $j->jenis_kelas ?? '-';
            $modeKelas = $j->mode_kelas ?? '-';
            $tentor = $j->tentor->nama ?? '-';
            $ruangan = $j->ruanganRef->nama_ruangan ?? $j->ruangan ?? '-';
            $cabang = $j->cabang->nama_cabang ?? '-';
            $pertemuan = $j->pertemuan ? 'Pertemuan ' . $j->pertemuan : '-';
            $statusRaw = $j->status ?? 'terjadwal';
            $status = ucfirst($statusRaw);
            $catatan = $j->catatan ?? '-';

            if ($statusRaw === 'selesai') $totalSelesai++;
            elseif ($statusRaw === 'dibatalkan') $totalDibatalkan++;
            else $totalTerjadwal++;

            $sheet->setCellValue('A' . $currentRow, $no++);
            $sheet->setCellValue('B' . $currentRow, $tglStr);
            $sheet->setCellValue('C' . $currentRow, $hari);
            $sheet->setCellValue('D' . $currentRow, $jamMulai);
            $sheet->setCellValue('E' . $currentRow, $jamSelesai);
            $sheet->setCellValue('F' . $currentRow, $program);
            $sheet->setCellValue('G' . $currentRow, $jenisKelas);
            $sheet->setCellValue('H' . $currentRow, $modeKelas);
            $sheet->setCellValue('I' . $currentRow, $tentor);
            $sheet->setCellValue('J' . $currentRow, $ruangan);
            $sheet->setCellValue('K' . $currentRow, $cabang);
            $sheet->setCellValue('L' . $currentRow, $pertemuan);
            $sheet->setCellValue('M' . $currentRow, $status);
            $sheet->setCellValue('N' . $currentRow, $catatan);

            // Row zebra striping
            $rowBg = ($no % 2 === 0) ? 'FAFAFC' : 'FFFFFF';
            $sheet->getStyle('A' . $currentRow . ':N' . $currentRow)->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $rowBg],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'E0E0E0'],
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);

            // Center alignment for specific columns
            foreach (['A', 'B', 'C', 'D', 'E', 'H', 'L', 'M'] as $col) {
                $sheet->getStyle($col . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }

            // Status color highlight
            if ($statusRaw === 'selesai') {
                $sheet->getStyle('M' . $currentRow)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('10B981'))->setBold(true);
            } elseif ($statusRaw === 'dibatalkan') {
                $sheet->getStyle('M' . $currentRow)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('EF4444'))->setBold(true);
            } else {
                $sheet->getStyle('M' . $currentRow)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('6366F1'))->setBold(true);
            }

            $sheet->getRowDimension($currentRow)->setRowHeight(20);
            $currentRow++;
        }

        // If no records
        if ($jadwals->isEmpty()) {
            $sheet->setCellValue('A' . $currentRow, 'Tidak ada jadwal yang ditemukan pada periode ini.');
            $sheet->mergeCells('A' . $currentRow . ':N' . $currentRow);
            $sheet->getStyle('A' . $currentRow)->applyFromArray([
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'font' => ['italic' => true, 'color' => ['rgb' => '86868B']],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E0E0E0']]],
            ]);
            $sheet->getRowDimension($currentRow)->setRowHeight(28);
            $currentRow++;
        }

        // 4. Summary Statistics Block
        $summaryRow = $currentRow + 1;
        $sheet->setCellValue('A' . $summaryRow, 'Total Jadwal: ' . $jadwals->count());
        $sheet->mergeCells('A' . $summaryRow . ':C' . $summaryRow);
        $sheet->getStyle('A' . $summaryRow)->getFont()->setBold(true)->setSize(10);

        $sheet->setCellValue('D' . $summaryRow, 'Selesai: ' . $totalSelesai . '   |   Terjadwal: ' . $totalTerjadwal . '   |   Dibatalkan: ' . $totalDibatalkan);
        $sheet->mergeCells('D' . $summaryRow . ':J' . $summaryRow);
        $sheet->getStyle('D' . $summaryRow)->getFont()->setBold(true)->setSize(10);

        $sheet->setCellValue('K' . $summaryRow, 'Dicetak pada: ' . Carbon::now()->locale('id')->isoFormat('DD/MM/YYYY HH:mm') . ' WIB');
        $sheet->mergeCells('K' . $summaryRow . ':N' . $summaryRow);
        $sheet->getStyle('K' . $summaryRow)->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
            'font' => ['italic' => true, 'size' => 9, 'color' => ['rgb' => '86868B']],
        ]);

        // Auto-fit all column widths
        foreach (range('A', 'N') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Stream response
        return new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment;filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
