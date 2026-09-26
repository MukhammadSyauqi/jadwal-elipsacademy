<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Jadwal;
use App\Models\Program;
use App\Models\Tentor;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminJadwalController extends Controller
{
    /**
     * Show the form for creating a new schedule.
     */
    public function create(Request $request): View
    {
        $user = $request->user();

        // Get active master data
        $cabangs = Cabang::where('status', 'aktif')->orderBy('nama_cabang')->get();
        $programs = Program::where('status', 'aktif')->orderBy('nama_program')->get();
        $tentors = Tentor::where('status', 'aktif')->orderBy('nama')->get();

        // Pre-select cabang from query param or user's assigned/first branch
        $selectedCabangId = $request->query('cabang_id');
        if (!$selectedCabangId) {
            if ($user && stripos($user->nama, 'Candi') !== false) {
                $candi = $cabangs->firstWhere('nama_cabang', 'Candi');
                $selectedCabangId = $candi ? $candi->id : ($cabangs->first()?->id ?? 1);
            } else {
                $selectedCabangId = $cabangs->first()?->id ?? 1;
            }
        }
        $selectedCabang = $cabangs->firstWhere('id', $selectedCabangId) ?? $cabangs->first();

        $selectedDate = $request->query('tanggal', Carbon::today()->toDateString());

        // Default room options
        $defaultRuangan = ['Ruang 1', 'Ruang 2', 'Lab Komputer A', 'Lab Komputer B'];

        return view('admin.jadwal.create', [
            'user' => $user,
            'cabangs' => $cabangs,
            'programs' => $programs,
            'tentors' => $tentors,
            'selectedCabang' => $selectedCabang,
            'selectedDate' => $selectedDate,
            'defaultRuangan' => $defaultRuangan,
        ]);
    }

    /**
     * Store a newly created schedule in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Auto-generate nama_kelas fallback if left empty
        if (empty($request->input('nama_kelas')) && $request->filled(['program_id', 'tanggal', 'pertemuan'])) {
            $program = Program::find($request->input('program_id'));
            if ($program) {
                try {
                    $tgl = Carbon::parse($request->input('tanggal'))->format('dm');
                    $pert = str_pad($request->input('pertemuan'), 2, '0', STR_PAD_LEFT);
                    $request->merge([
                        'nama_kelas' => "{$program->kode_inisial}-{$tgl}-{$pert}",
                    ]);
                } catch (\Exception $e) {
                    // Let validator handle invalid formats
                }
            }
        }

        $validated = $request->validate([
            'cabang_id' => ['required', Rule::exists('cabang', 'id')->where('status', 'aktif')],
            'program_id' => ['required', Rule::exists('program', 'id')->where('status', 'aktif')],
            'tentor_id' => ['required', Rule::exists('tentor', 'id')->where('status', 'aktif')],
            'nama_kelas' => 'required|string|max:100',
            'jenis_kelas' => 'required|in:private,rombel,business',
            'mode_kelas' => 'required|in:offline,online',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'ruangan' => 'required|string|max:100',
            'pertemuan' => 'required|integer|min:1|max:100',
            'catatan' => 'nullable|string|max:1000',
        ], [
            'jam_selesai.after' => 'Jam selesai harus lebih besar dari jam mulai.',
            'cabang_id.required' => 'Cabang wajib dipilih.',
            'program_id.required' => 'Program kursus wajib dipilih.',
            'tentor_id.required' => 'Tentor pengajar wajib dipilih.',
            'nama_kelas.required' => 'Nama/kode kelas wajib diisi.',
            'jenis_kelas.required' => 'Jenis kelas wajib dipilih.',
            'mode_kelas.required' => 'Mode kelas (Offline/Online) wajib dipilih.',
            'tanggal.required' => 'Tanggal pelaksanaan wajib diisi.',
            'jam_mulai.required' => 'Jam mulai wajib diisi.',
            'jam_selesai.required' => 'Jam selesai wajib diisi.',
            'ruangan.required' => 'Ruangan wajib dipilih/diisi.',
            'pertemuan.required' => 'Nomor pertemuan wajib diisi.',
        ]);

        // Check for schedule conflicts (tentor & ruangan)
        $this->detectConflicts($validated);

        // Set default status to 'terjadwal'
        $validated['status'] = 'terjadwal';

        // Save schedule
        $jadwal = Jadwal::create($validated);

        if ($request->filled('redirect_to')) {
            return redirect($request->input('redirect_to'))->with('success', 'Jadwal berhasil disimpan.');
        }

        if ($request->user() && $request->user()->role === 'superadmin') {
            return redirect()
                ->route('superadmin.jadwal.index')
                ->with('success', 'Jadwal berhasil disimpan.');
        }

        return redirect()
            ->route('admin.dashboard', [
                'cabang_id' => $jadwal->cabang_id,
                'tanggal' => $jadwal->tanggal->toDateString(),
            ])
            ->with('success', 'Jadwal berhasil disimpan.');
    }

    /**
     * Display the specified schedule detail.
     */
    public function show(Request $request, Jadwal $jadwal): View
    {
        $jadwal->load(['cabang', 'program', 'tentor']);
        $user = $request->user();

        return view('admin.jadwal.show', [
            'user' => $user,
            'jadwal' => $jadwal,
        ]);
    }

    /**
     * Show the form for editing the specified schedule.
     */
    public function edit(Request $request, Jadwal $jadwal): View
    {
        $jadwal->load(['cabang', 'program', 'tentor']);
        $user = $request->user();

        // Get master data: include active ones + the ones currently used by this schedule
        $cabangs = Cabang::where('status', 'aktif')
            ->orWhere('id', $jadwal->cabang_id)
            ->orderBy('nama_cabang')
            ->get();

        $programs = Program::where('status', 'aktif')
            ->orWhere('id', $jadwal->program_id)
            ->orderBy('nama_program')
            ->get();

        $tentors = Tentor::where('status', 'aktif')
            ->orWhere('id', $jadwal->tentor_id)
            ->orderBy('nama')
            ->get();

        $defaultRuangan = ['Ruang 1', 'Ruang 2', 'Lab Komputer A', 'Lab Komputer B'];
        if (!in_array($jadwal->ruangan, $defaultRuangan) && !empty($jadwal->ruangan)) {
            $defaultRuangan[] = $jadwal->ruangan;
        }

        return view('admin.jadwal.edit', [
            'user' => $user,
            'jadwal' => $jadwal,
            'cabangs' => $cabangs,
            'programs' => $programs,
            'tentors' => $tentors,
            'defaultRuangan' => $defaultRuangan,
        ]);
    }

    /**
     * Update the specified schedule in storage.
     */
    public function update(Request $request, Jadwal $jadwal): RedirectResponse
    {
        // Auto-generate nama_kelas fallback if left empty
        if (empty($request->input('nama_kelas')) && $request->filled(['program_id', 'tanggal', 'pertemuan'])) {
            $program = Program::find($request->input('program_id'));
            if ($program) {
                try {
                    $tgl = Carbon::parse($request->input('tanggal'))->format('dm');
                    $pert = str_pad($request->input('pertemuan'), 2, '0', STR_PAD_LEFT);
                    $request->merge([
                        'nama_kelas' => "{$program->kode_inisial}-{$tgl}-{$pert}",
                    ]);
                } catch (\Exception $e) {
                    // Let validator handle invalid formats
                }
            }
        }

        $validated = $request->validate([
            'cabang_id' => [
                'required',
                Rule::exists('cabang', 'id')->where(function ($query) use ($jadwal) {
                    $query->where('status', 'aktif')->orWhere('id', $jadwal->cabang_id);
                }),
            ],
            'program_id' => [
                'required',
                Rule::exists('program', 'id')->where(function ($query) use ($jadwal) {
                    $query->where('status', 'aktif')->orWhere('id', $jadwal->program_id);
                }),
            ],
            'tentor_id' => [
                'required',
                Rule::exists('tentor', 'id')->where(function ($query) use ($jadwal) {
                    $query->where('status', 'aktif')->orWhere('id', $jadwal->tentor_id);
                }),
            ],
            'nama_kelas' => 'required|string|max:100',
            'jenis_kelas' => 'required|in:private,rombel,business',
            'mode_kelas' => 'required|in:offline,online',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'ruangan' => 'required|string|max:100',
            'pertemuan' => 'required|integer|min:1|max:100',
            'status' => 'required|in:terjadwal,selesai,dibatalkan',
            'catatan' => 'nullable|string|max:1000',
        ], [
            'jam_selesai.after' => 'Jam selesai harus lebih besar dari jam mulai.',
            'cabang_id.required' => 'Cabang wajib dipilih.',
            'program_id.required' => 'Program kursus wajib dipilih.',
            'tentor_id.required' => 'Tentor pengajar wajib dipilih.',
            'nama_kelas.required' => 'Nama/kode kelas wajib diisi.',
            'jenis_kelas.required' => 'Jenis kelas wajib dipilih.',
            'mode_kelas.required' => 'Mode kelas (Offline/Online) wajib dipilih.',
            'tanggal.required' => 'Tanggal pelaksanaan wajib diisi.',
            'jam_mulai.required' => 'Jam mulai wajib diisi.',
            'jam_selesai.required' => 'Jam selesai wajib diisi.',
            'ruangan.required' => 'Ruangan wajib dipilih/diisi.',
            'pertemuan.required' => 'Nomor pertemuan wajib diisi.',
            'status.required' => 'Status jadwal wajib dipilih.',
        ]);

        // Only detect conflicts if schedule is not being set to 'dibatalkan'
        if ($validated['status'] !== 'dibatalkan') {
            $this->detectConflicts($validated, $jadwal->id);
        }

        $jadwal->update($validated);

        if ($request->filled('redirect_to')) {
            return redirect($request->input('redirect_to'))->with('success', 'Jadwal berhasil diperbarui.');
        }

        return redirect()
            ->route('admin.jadwal.show', $jadwal->id)
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    /**
     * Cancel the specified schedule (soft delete / status = dibatalkan).
     */
    public function batal(Request $request, Jadwal $jadwal): RedirectResponse
    {
        $jadwal->update(['status' => 'dibatalkan']);

        if ($request->filled('redirect_to')) {
            return redirect($request->input('redirect_to'))->with('success', 'Jadwal berhasil dibatalkan.');
        }

        if ($request->user() && $request->user()->role === 'superadmin') {
            return redirect()
                ->route('superadmin.jadwal.index')
                ->with('success', 'Jadwal berhasil dibatalkan.');
        }

        $tanggalStr = $jadwal->tanggal ? $jadwal->tanggal->toDateString() : Carbon::today()->toDateString();

        return redirect()
            ->route('admin.dashboard', [
                'cabang_id' => $jadwal->cabang_id,
                'tanggal' => $tanggalStr,
            ])
            ->with('success', 'Jadwal berhasil dibatalkan.');
    }

    /**
     * Optional permanent delete or cancellation fallback.
     */
    public function destroy(Request $request, Jadwal $jadwal): RedirectResponse
    {
        return $this->batal($request, $jadwal);
    }

    /**
     * Detect tentor and room schedule conflicts.
     *
     * Overlap formula (PRD 25.1):
     * start_A < end_B AND end_A > start_B on the same date.
     * Only schedules with status != 'dibatalkan' cause conflicts.
     *
     * @throws ValidationException
     */
    protected function detectConflicts(array $data, ?int $excludeId = null): void
    {
        $tanggal = $data['tanggal'];
        $jamMulai = strlen($data['jam_mulai']) === 5 ? $data['jam_mulai'] . ':00' : $data['jam_mulai'];
        $jamSelesai = strlen($data['jam_selesai']) === 5 ? $data['jam_selesai'] . ':00' : $data['jam_selesai'];
        $tentorId = $data['tentor_id'];
        $cabangId = $data['cabang_id'];
        $ruangan = $data['ruangan'];

        // 1. Tentor Conflict (PRD 25.2)
        $tentorConflict = Jadwal::with('tentor')
            ->where('tanggal', $tanggal)
            ->where('tentor_id', $tentorId)
            ->where('status', '!=', 'dibatalkan')
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->where(function ($q) use ($jamMulai, $jamSelesai) {
                $q->where('jam_mulai', '<', $jamSelesai)
                  ->where('jam_selesai', '>', $jamMulai);
            })
            ->first();

        if ($tentorConflict) {
            $tentorName = $tentorConflict->tentor->nama ?? 'Tentor';
            $jamRange = substr($tentorConflict->jam_mulai, 0, 5) . ' - ' . substr($tentorConflict->jam_selesai, 0, 5);

            throw ValidationException::withMessages([
                'tentor_id' => "Conflict Detected: Tentor {$tentorName} sudah memiliki jadwal lain pada waktu tersebut ({$tentorConflict->nama_kelas}, {$jamRange} WIB).",
            ]);
        }

        // 2. Room Conflict (PRD 25.3)
        $ruanganConflict = Jadwal::where('tanggal', $tanggal)
            ->where('cabang_id', $cabangId)
            ->where('ruangan', $ruangan)
            ->where('status', '!=', 'dibatalkan')
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->where(function ($q) use ($jamMulai, $jamSelesai) {
                $q->where('jam_mulai', '<', $jamSelesai)
                  ->where('jam_selesai', '>', $jamMulai);
            })
            ->first();

        if ($ruanganConflict) {
            $jamRange = substr($ruanganConflict->jam_mulai, 0, 5) . ' - ' . substr($ruanganConflict->jam_selesai, 0, 5);

            throw ValidationException::withMessages([
                'ruangan' => "Conflict Detected: {$ruangan} sudah digunakan untuk kelas lain pada waktu tersebut ({$ruanganConflict->nama_kelas}, {$jamRange} WIB).",
            ]);
        }
    }
}
