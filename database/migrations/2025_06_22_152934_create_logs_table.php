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
        Schema::create('tb_log', function (Blueprint $table) {
            $table->id('ID_Log');
            $table->foreignId('ID_Akun')->constrained('tb_akun', 'ID_Akun');
            $table->string('keterangan');
            $table->date('tanggal');
            $table->time('time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
