<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';

    protected $fillable = [
        'cabang_id',
        'program_id',
        'tentor_id',
        'nama_kelas',
        'jenis_kelas',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'ruangan',
        'pertemuan',
        'status',
        'catatan',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'pertemuan' => 'integer',
        ];
    }

    /**
     * Get the cabang that owns this jadwal.
     */
    public function cabang(): BelongsTo
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    /**
     * Get the program that owns this jadwal.
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    /**
     * Get the tentor that owns this jadwal.
     */
    public function tentor(): BelongsTo
    {
        return $this->belongsTo(Tentor::class, 'tentor_id');
    }

    /**
     * Get the dynamic display status.
     * Values: 'selesai', 'dibatalkan', 'sedang_berlangsung', 'akan_datang'
     */
    public function getDisplayStatusAttribute(): string
    {
        if ($this->status === 'dibatalkan') {
            return 'dibatalkan';
        }

        if ($this->status === 'selesai') {
            return 'selesai';
        }

        $now = now();
        $isToday = $this->tanggal ? $this->tanggal->isToday() : false;
        $currentTime = $now->format('H:i:s');

        if ($isToday) {
            if ($currentTime >= $this->jam_mulai && $currentTime <= $this->jam_selesai) {
                return 'sedang_berlangsung';
            }
        }

        return 'akan_datang';
    }

    /**
     * Get the session category of the schedule.
     * Values: 'pagi', 'siang', 'sore', 'malam'
     */
    public function getSesiAttribute(): string
    {
        $jam = substr($this->jam_mulai, 0, 5);

        if ($jam < '12:00') {
            return 'pagi';
        }
        if ($jam < '15:30') {
            return 'siang';
        }
        if ($jam < '18:30') {
            return 'sore';
        }

        return 'malam';
    }

    /**
     * Get formatted duration in hours (e.g. '2 Jam').
     */
    public function getDurasiAttribute(): string
    {
        if (!$this->jam_mulai || !$this->jam_selesai) {
            return '2 Jam';
        }

        try {
            $start = \Carbon\Carbon::createFromFormat('H:i:s', strlen($this->jam_mulai) === 5 ? $this->jam_mulai . ':00' : $this->jam_mulai);
            $end = \Carbon\Carbon::createFromFormat('H:i:s', strlen($this->jam_selesai) === 5 ? $this->jam_selesai . ':00' : $this->jam_selesai);
            $diffMinutes = $start->diffInMinutes($end);
            $hours = $diffMinutes / 60;

            if ($hours == (int)$hours) {
                return ((int)$hours) . ' Jam';
            }

            return rtrim(rtrim(number_format($hours, 1), '0'), '.') . ' Jam';
        } catch (\Exception $e) {
            return '2 Jam';
        }
    }

    /**
     * Get formatted time range e.g. '09:00 - 11:00'.
     */
    public function getFormattedJamAttribute(): string
    {
        $mulai = substr($this->jam_mulai, 0, 5);
        $selesai = substr($this->jam_selesai, 0, 5);

        return "{$mulai} - {$selesai}";
    }
}
