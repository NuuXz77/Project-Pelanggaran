<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'tb_kelas';
    protected $primaryKey = 'ID_Kelas';
    protected $fillable = ['kelas', 'jurusan'];

    // Jumlah siswa tidak perlu di fillable karena dihitung otomatis
    protected $appends = ['jumlah_siswa'];

    public function getJumlahSiswaAttribute()
    {
        return $this->siswa()->count();
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }

    // public function waliKelas()
    // {
    //     return $this->belongsTo(Guru::class, 'wali_kelas');
    // }

    public function pelanggaran()
    {
        return $this->hasManyThrough(
            Pelanggaran::class,
            Siswa::class,
            'kelas_id',
            'siswa_id'
        );
    }
}