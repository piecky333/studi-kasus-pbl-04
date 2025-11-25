<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class HasilAkhir extends Model
{
    protected $table = 'hasil_akhir';
    protected $primaryKey = 'id_hasil';
    public $timestamps = false; 

    protected $fillable = [
        'id_alternatif',
        'skor_akhir',
        'rangking',
        // 'bobot_digunakan', // Uncomment if needed in migration
    ];

    public function alternatif()
    {
        return $this->belongsTo(alternatif::class, 'id_alternatif');
    }
}