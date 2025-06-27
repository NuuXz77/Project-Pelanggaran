<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tindakan extends Model
{
    protected $table = 'tb_tindakan';
    protected $primaryKey = 'ID_Tindakan'; // jika tidak pakai id auto-increment, sesuaikan

    protected $fillable = [
        'kode_tindakan',
        'jenis',
        'keterangan',
    ];

    public $timestamps = false; // karena tidak ada kolom created_at dan updated_at
}
