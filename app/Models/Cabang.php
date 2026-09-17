<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cabang extends Model
{
    use HasFactory;

    protected $table = 'cabang';

    protected $fillable = [
        'nama_cabang',
        'alamat',
        'status',
    ];

    /**
     * Get the jadwals for this cabang.
     */
    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'cabang_id');
    }
}
