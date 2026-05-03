<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\HasHashid;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Alternatif extends Model
{
    use HasFactory, HasHashid;

    protected $table = 'alternatif';
    protected $primaryKey = 'id_alternatif';
    public $timestamps = false;

    protected $fillable = [
        'id_keputusan',
        'id_mahasiswa',
        'nama_alternatif',
        'keterangan',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(\App\Models\DataMahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function keputusan(): BelongsTo
    {
        return $this->belongsTo(SpkKeputusan::class, 'id_keputusan');
    }

    public function penilaian(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'id_alternatif');
    }

    public function hasilAkhir(): HasOne
    {
        return $this->hasOne(HasilAkhir::class, 'id_alternatif');
    }
}

