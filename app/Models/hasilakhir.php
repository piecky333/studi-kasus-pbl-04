<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class HasilAkhir extends Model
{
    protected $table = 'hasil_akhir';
    protected $primaryKey = 'id_hasil_akhir';
    public $timestamps = false; 

    protected $fillable = [
        'id_keputusan',
        'id_alternatif',
        'nilai_preferensi',
        'ranking',
        'bobot_digunakan', 
    ];

    public function alternatif()
    {
        return $this->belongsTo(alternatif::class, 'id_alternatif');
    }
}