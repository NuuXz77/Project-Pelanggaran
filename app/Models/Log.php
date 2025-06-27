<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'tb_log';
    protected $primaryKey = 'ID_Log';
    protected $fillable = ['ID_Akun', 'keterangan', 'tanggal', 'time'];

    public function akun()
    {
        return $this->belongsTo(User::class, 'ID_Akun');
    }
}
