<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'tb_siswa';
    protected $primaryKey = 'ID_Siswa';
    protected $fillable = ['kelas_id', 'nis', 'nama_siswa', 'total_pelanggaran'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
    public function pelanggaran()
    {
        return $this->hasMany(Pelanggaran::class, 'siswa_id');
    }
}
