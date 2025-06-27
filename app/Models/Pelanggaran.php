<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggaran extends Model
{
    protected $table = 'tb_pelanggaran';
    protected $primaryKey = 'ID_Pelanggaran';
    protected $fillable = [
        'siswa_id',
        'kelas_id',
        'peraturan_id',
        'nis',
        'nama_siswa',
        'kelas',
        'tingkat_pelanggaran',
        'sanksi',
        'deskripsi_pelanggaran'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
    public function peraturan()
    {
        return $this->belongsTo(Peraturan::class, 'peraturan_id');
    }
}
