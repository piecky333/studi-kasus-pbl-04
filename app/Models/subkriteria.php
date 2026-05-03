<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\HasHashid;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubKriteria extends Model
{
    use HasFactory, HasHashid;

    protected $table = 'subkriteria';
    protected $primaryKey = 'id_subkriteria';
    public $timestamps = false;

    protected $fillable = [
        'id_kriteria',
        'nama_subkriteria',
        'nilai',
        'id_keputusan',
    ];

    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class, 'id_kriteria');
    }

    public function keputusan(): BelongsTo
    {
        return $this->belongsTo(SpkKeputusan::class, 'id_keputusan');
    }
}

