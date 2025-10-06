<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Terlapor extends Model
{
    protected $table = 'terlapor';
    protected $primaryKey = 'id_terlapor';
    protected $fillable = ['nama', 'status_terlapor', 'no_hp_terlapor', 'id_laporan'];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'id_laporan');
    }
}

