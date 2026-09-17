<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tentor extends Model
{
    use HasFactory;

    protected $table = 'tentor';

    protected $fillable = [
        'nama',
        'no_hp',
        'keahlian',
        'status',
    ];

    /**
     * Get the jadwals for this tentor.
     */
    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'tentor_id');
    }
}
