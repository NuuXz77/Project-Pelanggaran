<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'tb_kelas';
    protected $primaryKey = 'ID_Kelas';
    protected $fillable = ['kelas', 'jurusan', 'wali_kelas', 'jumlah_siswa'];

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }
    public function pelanggaran()
    {
        return $this->hasMany(Pelanggaran::class, 'kelas_id');
    }
    public function guru()
    {
        return $this->hasOne(Guru::class, 'kelas_id');
    }
}
