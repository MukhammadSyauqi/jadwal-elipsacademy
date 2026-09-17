<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    use HasFactory;

    protected $table = 'program';

    protected $fillable = [
        'nama_program',
        'kategori',
        'status',
    ];

    /**
     * Get the jadwals for this program.
     */
    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'program_id');
    }
}
