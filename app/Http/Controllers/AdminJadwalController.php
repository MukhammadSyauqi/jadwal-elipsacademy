<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Jadwal;
use App\Models\Program;
use App\Models\Ruangan;
use App\Models\Tentor;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
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

        // Pre-select cabang: if admin, lock to assigned cabang. If superadmin, allow all active branches.
        if ($user && $user->cabang_id !== null) {
            $cabangs = Cabang::where('id', $user->cabang_id)->get();
            $selectedCabang = $cabangs->first();
        } else {
            $cabangs = Cabang::where('status', 'aktif')->orderBy('nama_cabang')->get();
            $selectedCabangId = $request->query('cabang_id');
            $selectedCabang = $selectedCabangId ? $cabangs->firstWhere('id', $selectedCabangId) : $cabangs->first();
        }

        $programs = Program::where('status', 'aktif')->orderBy('nama_program')->get();
        $tentors = Tentor::where('status', 'aktif')->orderBy('nama')->get();

        $selectedDate = $request->query('tanggal', Carbon::today()->toDateString());

        // Ambil ruangan aktif dari database berdasarkan cabang yang dipilih
        $ruangans = $selectedCabang
            ? Ruangan::where('cabang_id', $selectedCabang->id)
                ->where('status', 'aktif')
                ->orderBy('nama_ruangan')
                ->get()
            : collect();

        return view('admin.jadwal.create', [
            'user' => $user,
            'cabangs' => $cabangs,
            'programs' => $programs,
            'tentors' => $tentors,
            'selectedCabang' => $selectedCabang,
            'selectedDate' => $selectedDate,
            'ruangans' => $ruangans,
            'defaultRuangan' => $ruangans->pluck('nama_ruangan')->toArray(),
        ]);
    }

    /**
     * Store a newly created schedule in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        if ($user && $user->cabang_id !== null) {
            $request->merge(['cabang_id' => $user->cabang_id]);
        }

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

        // Sinkronisasi ruangan_id dan nama ruangan (text)
        if ($request->filled('ruangan_id')) {
            $ruanganObj = Ruangan::find($request->input('ruangan_id'));
            if ($ruanganObj && empty($request->input('ruangan'))) {
                $request->merge(['ruangan' => $ruanganObj->nama_ruangan]);
            }
        } elseif ($request->filled('ruangan') && $request->filled('cabang_id')) {
            $ruanganObj = Ruangan::where('cabang_id', $request->input('cabang_id'))
                ->where('nama_ruangan', $request->input('ruangan'))
                ->first();
            if ($ruanganObj) {
                $request->merge(['ruangan_id' => $ruanganObj->id]);
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
            'ruangan_id' => ['required_without:ruangan', 'nullable', Rule::exists('ruangan', 'id')],
            'ruangan' => ['required_without:ruangan_id', 'nullable', 'string', 'max:100'],
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
            'ruangan_id.required_without' => 'Ruangan wajib dipilih/diisi.',
            'ruangan.required_without' => 'Ruangan wajib dipilih/diisi.',
            'pertemuan.required' => 'Nomor pertemuan wajib diisi.',
        ]);

        // 1. Cek bentrok tentor (selalu hard block)
        $this->detectTentorConflict($validated);

        // 2. Cek bentrok ruangan (soft warning dengan force_room)
        $forceRoom = $request->boolean('force_room');
        $conflictingRoomJadwal = $this->getRoomConflict($validated);

        if ($conflictingRoomJadwal) {
            if (!$forceRoom) {
                $this->throwRoomConflictException($conflictingRoomJadwal, $validated['ruangan'] ?? 'Ruangan');
            } else {
                $jamRange = substr($conflictingRoomJadwal->jam_mulai, 0, 5) . ' - ' . substr($conflictingRoomJadwal->jam_selesai, 0, 5);
                $auditNote = "[OVERRIDE] Dijadwalkan meskipun ada konflik ruangan dengan kelas {$conflictingRoomJadwal->nama_kelas} pada jam {$jamRange}.";
                $userCatatan = $validated['catatan'] ?? '';
                $validated['catatan'] = trim($auditNote . ($userCatatan ? "\n" . $userCatatan : ''));
            }
        }

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
        $jadwal->load(['cabang', 'program', 'tentor', 'ruanganRef']);
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
        $jadwal->load(['cabang', 'program', 'tentor', 'ruanganRef']);
        $user = $request->user();

        // Guard: admin cannot edit schedules of other branches
        if ($user && $user->cabang_id !== null && $jadwal->cabang_id !== $user->cabang_id) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengedit jadwal di luar cabang Anda.');
        }

        // Get master data: include active ones + the ones currently used by this schedule
        if ($user && $user->cabang_id !== null) {
            $cabangs = Cabang::where('id', $user->cabang_id)->get();
        } else {
            $cabangs = Cabang::where('status', 'aktif')
                ->orWhere('id', $jadwal->cabang_id)
                ->orderBy('nama_cabang')
                ->get();
        }

        $programs = Program::where('status', 'aktif')
            ->orWhere('id', $jadwal->program_id)
            ->orderBy('nama_program')
            ->get();

        $tentors = Tentor::where('status', 'aktif')
            ->orWhere('id', $jadwal->tentor_id)
            ->orderBy('nama')
            ->get();

        // Ambil ruangan dari database: status aktif ATAU ruangan yang sedang digunakan jadwal ini
        $ruangans = Ruangan::where('cabang_id', $jadwal->cabang_id)
            ->where(function ($q) use ($jadwal) {
                $q->where('status', 'aktif');
                if ($jadwal->ruangan_id) {
                    $q->orWhere('id', $jadwal->ruangan_id);
                }
            })
            ->orderBy('nama_ruangan')
            ->get();

        // Jika ruangan_id null tetapi ada nama ruangan text di record lama, cocokkan bila ada
        if (!$jadwal->ruangan_id && !empty($jadwal->ruangan)) {
            $matched = $ruangans->firstWhere('nama_ruangan', $jadwal->ruangan);
            if ($matched) {
                $jadwal->ruangan_id = $matched->id;
            }
        }

        return view('admin.jadwal.edit', [
            'user' => $user,
            'jadwal' => $jadwal,
            'cabangs' => $cabangs,
            'programs' => $programs,
            'tentors' => $tentors,
            'ruangans' => $ruangans,
            'defaultRuangan' => $ruangans->pluck('nama_ruangan')->toArray(),
        ]);
    }

    /**
     * Update the specified schedule in storage.
     */
    public function update(Request $request, Jadwal $jadwal): RedirectResponse
    {
        $user = $request->user();

        // Guard: admin cannot update schedules of other branches
        if ($user && $user->cabang_id !== null && $jadwal->cabang_id !== $user->cabang_id) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah jadwal di luar cabang Anda.');
        }

        if ($user && $user->cabang_id !== null) {
            $request->merge(['cabang_id' => $user->cabang_id]);
        }

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

        // Sinkronisasi ruangan_id dan nama ruangan (text)
        if ($request->filled('ruangan_id')) {
            $ruanganObj = Ruangan::find($request->input('ruangan_id'));
            if ($ruanganObj && empty($request->input('ruangan'))) {
                $request->merge(['ruangan' => $ruanganObj->nama_ruangan]);
            }
        } elseif ($request->filled('ruangan') && $request->filled('cabang_id')) {
            $ruanganObj = Ruangan::where('cabang_id', $request->input('cabang_id'))
                ->where('nama_ruangan', $request->input('ruangan'))
                ->first();
            if ($ruanganObj) {
                $request->merge(['ruangan_id' => $ruanganObj->id]);
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
            'ruangan_id' => [
                'required_without:ruangan',
                'nullable',
                Rule::exists('ruangan', 'id')->where(function ($query) use ($jadwal) {
                    $query->where('status', 'aktif')->orWhere('id', $jadwal->ruangan_id);
                }),
            ],
            'ruangan' => ['required_without:ruangan_id', 'nullable', 'string', 'max:100'],
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
            'ruangan_id.required_without' => 'Ruangan wajib dipilih/diisi.',
            'ruangan.required_without' => 'Ruangan wajib dipilih/diisi.',
            'pertemuan.required' => 'Nomor pertemuan wajib diisi.',
            'status.required' => 'Status jadwal wajib dipilih.',
        ]);

        // Only detect conflicts if schedule is not being set to 'dibatalkan'
        if ($validated['status'] !== 'dibatalkan') {
            // 1. Cek bentrok tentor (selalu hard block)
            $this->detectTentorConflict($validated, $jadwal->id);

            // 2. Cek bentrok ruangan (soft warning dengan force_room)
            $forceRoom = $request->boolean('force_room');
            $conflictingRoomJadwal = $this->getRoomConflict($validated, $jadwal->id);

            if ($conflictingRoomJadwal) {
                if (!$forceRoom) {
                    $this->throwRoomConflictException($conflictingRoomJadwal, $validated['ruangan'] ?? 'Ruangan');
                } else {
                    $jamRange = substr($conflictingRoomJadwal->jam_mulai, 0, 5) . ' - ' . substr($conflictingRoomJadwal->jam_selesai, 0, 5);
                    $auditNote = "[OVERRIDE] Dijadwalkan meskipun ada konflik ruangan dengan kelas {$conflictingRoomJadwal->nama_kelas} pada jam {$jamRange}.";
                    $userCatatan = $validated['catatan'] ?? '';
                    if (!str_contains($userCatatan, '[OVERRIDE]')) {
                        $validated['catatan'] = trim($auditNote . ($userCatatan ? "\n" . $userCatatan : ''));
                    }
                }
            }
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
        $user = $request->user();

        // Guard: admin cannot cancel schedules of other branches
        if ($user && $user->cabang_id !== null && $jadwal->cabang_id !== $user->cabang_id) {
            abort(403, 'Anda tidak memiliki hak akses untuk membatalkan jadwal di luar cabang Anda.');
        }

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
     * Check if a room has any schedule conflicts at the requested time.
     * Endpoint: POST /admin/jadwal/check-room-conflict
     */
    public function checkRoomConflict(Request $request): JsonResponse
    {
        $request->validate([
            'cabang_id' => 'required',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        $cabangId = $request->input('cabang_id');
        $ruanganId = $request->input('ruangan_id');
        $ruanganName = $request->input('ruangan');
        $tanggal = $request->input('tanggal');
        $jamMulai = $request->input('jam_mulai');
        $jamSelesai = $request->input('jam_selesai');
        $excludeId = $request->input('exclude_id');

        if ($ruanganId && empty($ruanganName)) {
            $rObj = Ruangan::find($ruanganId);
            $ruanganName = $rObj?->nama_ruangan;
        }

        if (strlen($jamMulai) === 5) $jamMulai .= ':00';
        if (strlen($jamSelesai) === 5) $jamSelesai .= ':00';

        $conflicts = Jadwal::with(['program', 'tentor', 'ruanganRef'])
            ->where('tanggal', $tanggal)
            ->where('cabang_id', $cabangId)
            ->where('status', '!=', 'dibatalkan')
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->where(function ($q) use ($ruanganId, $ruanganName) {
                if ($ruanganId) {
                    $q->where('ruangan_id', $ruanganId);
                    if ($ruanganName) {
                        $q->orWhere('ruangan', $ruanganName);
                    }
                } else {
                    $q->where('ruangan', $ruanganName);
                }
            })
            ->where(function ($q) use ($jamMulai, $jamSelesai) {
                $q->where('jam_mulai', '<', $jamSelesai)
                  ->where('jam_selesai', '>', $jamMulai);
            })
            ->get();

        $conflictList = $conflicts->map(function ($j) {
            $jamRange = substr($j->jam_mulai, 0, 5) . ' - ' . substr($j->jam_selesai, 0, 5);
            return [
                'id' => $j->id,
                'nama_kelas' => $j->nama_kelas,
                'program' => $j->program->nama_program ?? 'Program',
                'jam' => $jamRange,
                'tentor' => $j->tentor->nama ?? 'Tentor',
                'ruangan' => $j->ruanganRef->nama_ruangan ?? $j->ruangan ?? 'Ruangan',
            ];
        });

        return response()->json([
            'has_conflict' => $conflicts->isNotEmpty(),
            'conflicts' => $conflictList,
        ]);
    }

    /**
     * Get active rooms for a given branch.
     * Endpoint: GET /api/ruangan?cabang_id=X
     */
    public function getRuanganByCabang(Request $request): JsonResponse
    {
        $cabangId = $request->query('cabang_id');
        $includeId = $request->query('include_id');

        if (!$cabangId) {
            return response()->json([]);
        }

        $ruangans = Ruangan::where('cabang_id', $cabangId)
            ->where(function ($q) use ($includeId) {
                $q->where('status', 'aktif');
                if ($includeId) {
                    $q->orWhere('id', $includeId);
                }
            })
            ->orderBy('nama_ruangan')
            ->get(['id', 'cabang_id', 'nama_ruangan', 'kapasitas', 'status']);

        return response()->json($ruangans);
    }

    /**
     * Detect tentor conflict. Always hard block.
     *
     * @throws ValidationException
     */
    protected function detectTentorConflict(array $data, ?int $excludeId = null): void
    {
        $tanggal = $data['tanggal'];
        $jamMulai = strlen($data['jam_mulai']) === 5 ? $data['jam_mulai'] . ':00' : $data['jam_mulai'];
        $jamSelesai = strlen($data['jam_selesai']) === 5 ? $data['jam_selesai'] . ':00' : $data['jam_selesai'];
        $tentorId = $data['tentor_id'];

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
    }

    /**
     * Get conflicting room schedule if any.
     */
    protected function getRoomConflict(array $data, ?int $excludeId = null): ?Jadwal
    {
        $tanggal = $data['tanggal'];
        $jamMulai = strlen($data['jam_mulai']) === 5 ? $data['jam_mulai'] . ':00' : $data['jam_mulai'];
        $jamSelesai = strlen($data['jam_selesai']) === 5 ? $data['jam_selesai'] . ':00' : $data['jam_selesai'];
        $cabangId = $data['cabang_id'];
        $ruanganId = $data['ruangan_id'] ?? null;
        $ruanganName = $data['ruangan'] ?? null;

        if (!$ruanganId && !$ruanganName) {
            return null;
        }

        return Jadwal::with(['program', 'tentor', 'ruanganRef'])
            ->where('tanggal', $tanggal)
            ->where('cabang_id', $cabangId)
            ->where('status', '!=', 'dibatalkan')
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->where(function ($q) use ($ruanganId, $ruanganName) {
                if ($ruanganId) {
                    $q->where('ruangan_id', $ruanganId);
                    if ($ruanganName) {
                        $q->orWhere('ruangan', $ruanganName);
                    }
                } else {
                    $q->where('ruangan', $ruanganName);
                }
            })
            ->where(function ($q) use ($jamMulai, $jamSelesai) {
                $q->where('jam_mulai', '<', $jamSelesai)
                  ->where('jam_selesai', '>', $jamMulai);
            })
            ->first();
    }

    /**
     * Throw validation exception for room conflict.
     *
     * @throws ValidationException
     */
    protected function throwRoomConflictException(Jadwal $conflict, string $ruanganName): void
    {
        $jamRange = substr($conflict->jam_mulai, 0, 5) . ' - ' . substr($conflict->jam_selesai, 0, 5);
        $message = "Conflict Detected: {$ruanganName} sudah digunakan untuk kelas lain pada waktu tersebut ({$conflict->nama_kelas}, {$jamRange} WIB).";

        throw ValidationException::withMessages([
            'ruangan' => $message,
            'ruangan_id' => $message,
        ]);
    }

    /**
     * Backwards-compatible detectConflicts method.
     */
    protected function detectConflicts(array $data, ?int $excludeId = null): void
    {
        $this->detectTentorConflict($data, $excludeId);
        $conflict = $this->getRoomConflict($data, $excludeId);
        if ($conflict) {
            $this->throwRoomConflictException($conflict, $data['ruangan'] ?? 'Ruangan');
        }
    }
}
