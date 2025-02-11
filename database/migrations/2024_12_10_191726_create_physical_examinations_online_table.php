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
        Schema::create('physical_examinations_online', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel patients
            $table->foreignId('patient_id')->constrained('patients_online');
            $table->unsignedBigInteger('paramedis_id')->nullable();
            $table->unsignedBigInteger('doctor_id')->nullable();
            // Kolom-kolom untuk data pemeriksaan fisik
            $table->string('blood_pressure')->nullable();
            $table->integer('heart_rate')->nullable();
            $table->integer('oxygen_saturation')->nullable();
            $table->integer('respiratory_rate')->nullable();
            $table->decimal('body_temperature', 5, 2)->nullable();
            $table->text('physical_assessment')->nullable();
            $table->text('reason')->nullable();
            $table->text('medical_advice')->nullable();
            $table->enum('health_status', ['healthy', 'butuh_dokter', 'butuh_pendamping'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('physical_examinations_online');
    }
};
