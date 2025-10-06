<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sanksi extends Model
{
    protected $table = 'sanksi';
    protected $primaryKey = 'id_kodetik';
    protected $fillable = ['id_mhsbermasalah', 'tanggal_sanksi', 'jenis_sanksi'];

    public function mahasiswaBermasalah()
    {
        return $this->belongsTo(Mahasiswa_bermasalah::class, 'id_mhsbermasalah');
    }
}

