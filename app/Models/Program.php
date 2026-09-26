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

    /**
     * Generate uppercase initials from program name.
     * E.g. "Microsoft Office" -> "MO", "Web Programming" -> "WP", "Graphic Design" -> "GD".
     */
    public static function generateKodeInisial(?string $namaProgram): string
    {
        if (empty($namaProgram)) {
            return 'CLS';
        }

        $words = preg_split('/\s+/', trim($namaProgram));
        if (count($words) >= 2) {
            $initials = '';
            foreach ($words as $w) {
                if (!empty($w)) {
                    $initials .= mb_strtoupper(mb_substr($w, 0, 1));
                }
            }
            return substr($initials, 0, 4);
        }

        return strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $namaProgram), 0, 3));
    }

    /**
     * Accessor for kode_inisial attribute.
     */
    public function getKodeInisialAttribute(): string
    {
        return self::generateKodeInisial($this->nama_program);
    }
}
