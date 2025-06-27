<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $table = 'tb_guru';
    protected $primaryKey = 'ID_Guru';
    protected $fillable = ['kelas_id', 'nama_guru', 'nip', 'password', 'kelas'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
