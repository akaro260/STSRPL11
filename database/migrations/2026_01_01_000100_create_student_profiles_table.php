<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('nisn')->nullable();
            $table->string('kelas')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('telp')->nullable();
            $table->string('alamat')->nullable();
            $table->string('wali')->nullable();
            $table->string('telp_wali')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};
