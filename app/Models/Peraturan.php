<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peraturan extends Model
{
    protected $table = 'tb_peraturan';
    protected $primaryKey = 'ID_Peraturan';
    // protected $guarded = [];
    protected $fillable = ['kode_peraturan', 'larangan', 'tindakan_ringan', 'tindakan_berat'];

    public function pelanggaran()
    {
        return $this->hasMany(Pelanggaran::class, 'peraturan_id');
    }

    // Di model Peraturan
    public function tindakanRingan()
    {
        return $this->belongsTo(Tindakan::class, 'tindakan_ringan', 'kode_tindakan');
    }

    public function tindakanBerat()
    {
        return $this->belongsTo(Tindakan::class, 'tindakan_berat', 'kode_tindakan');
    }
}
