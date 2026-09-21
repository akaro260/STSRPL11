<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permohonan', function (Blueprint $table) {
            $table->id();
            $table->string('no')->unique();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->string('category'); // cuti, dokumen, lainnya
            $table->string('title');
            $table->text('description');
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->string('status')->default('Diajukan');
            $table->timestamp('decided_at')->nullable();
            $table->timestamps();
        });

        Schema::create('permohonan_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_id')->constrained('permohonan')->cascadeOnDelete();
            $table->string('actor_name');
            $table->string('role');
            $table->string('status');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permohonan_steps');
        Schema::dropIfExists('permohonan');
    }
};
