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
}
