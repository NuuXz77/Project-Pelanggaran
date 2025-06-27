<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tb_pelanggaran', function (Blueprint $table) {
            $table->id('ID_Pelanggaran');
            $table->foreignId('siswa_id')->constrained('tb_siswa', 'ID_Siswa');
            $table->foreignId('kelas_id')->constrained('tb_kelas', 'ID_Kelas');
            $table->foreignId('peraturan_id')->constrained('tb_peraturan', 'ID_Peraturan');
            $table->foreignId('tindakan_id')->constrained('tb_tindakan', 'ID_Tindakan');
            $table->string('nis'); // redundan tapi tetap dicatat
            $table->string('nama_siswa'); // redundan
            $table->string('kelas'); // redundan
            $table->string('pelanggaran'); // redundan
            $table->string('tingkat_pelanggaran');
            $table->string('tindakan');
            $table->text('deskripsi_pelanggaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggarans');
    }
};
