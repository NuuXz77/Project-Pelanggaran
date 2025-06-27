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
        Schema::create('tb_guru', function (Blueprint $table) {
            $table->id('ID_Guru');
            $table->foreignId('kelas_id')->constrained('tb_kelas', 'ID_Kelas');
            $table->string('nama_guru');
            $table->string('nip')->unique();
            $table->string('password');
            $table->string('kelas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gurus');
    }
};
