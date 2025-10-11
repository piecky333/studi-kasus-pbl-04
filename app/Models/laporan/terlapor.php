<?php

namespace App\Models\laporan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class terlapor extends Model
{
    use HasFactory;

    protected $table = 'terlapor';
    protected $primaryKey = 'id_terlapor';
    protected $fillable = [
        'id_laporan',
        'nama',
        'no_hp_terlapor',
        'status_terlapor'
    ];

    public function laporan()
    {
        return $this->belongsTo(laporan::class, 'id_laporan', 'id_laporan');
    }
}
