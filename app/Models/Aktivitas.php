<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aktivitas extends Model
{
    protected $table = 'tb_aktivitas';
    protected $primaryKey = 'ID_Aktivitas';
    protected $fillable = ['ID_Akun', 'keterangan', 'tanggal', 'time'];

    public function akun()
    {
        return $this->belongsTo(User::class, 'ID_Akun');
    }
}
