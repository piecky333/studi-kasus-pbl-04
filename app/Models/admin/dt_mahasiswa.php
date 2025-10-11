<?php
<<<<<<< HEAD
=======
namespace App\Models\admin;
>>>>>>> 0dc1741 (Menambahkan PrestasiController dan memperbarui model serta view terkait)

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dt_mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'dt_mahasiswa';
<<<<<<< HEAD
    protected $primaryKey = 'id_dtmahasiswa';
    protected $fillable = [
        'nim',
        'nama',
        'email',
        'semester'
    ];

    public function prestasi()
    {
        return $this->hasMany(prestasi::class, 'id_dtmahasiswa', 'id_dtmahasiswa');
=======
    protected $primaryKey = 'id_mahasiswa';
    protected $fillable = ['id_admin', 'nama', 'nim', 'semester', 'alamat', 'no_hp'];

    public function prestasi()
    {
        return $this->hasMany(Prestasi::class, 'id_mahasiswa');
>>>>>>> 0dc1741 (Menambahkan PrestasiController dan memperbarui model serta view terkait)
    }

    public function sanksi()
    {
<<<<<<< HEAD
        return $this->hasMany(sanksi::class, 'id_dtmahasiswa', 'id_dtmahasiswa');
=======
        return $this->hasMany(\App\Models\admin\mahasiswa_bermasalah::class, 'id_mahasiswa');
>>>>>>> 0dc1741 (Menambahkan PrestasiController dan memperbarui model serta view terkait)
    }

    public function keuangan()
    {
<<<<<<< HEAD
        return $this->hasMany(keuangan::class, 'id_anggota', 'id_dtmahasiswa');
=======
        return $this->hasOne(\App\Models\anggota::class, 'id_mhs');
>>>>>>> 0dc1741 (Menambahkan PrestasiController dan memperbarui model serta view terkait)
    }
}
