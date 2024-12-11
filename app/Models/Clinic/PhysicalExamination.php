<?php

namespace App\Models\Clinic;

use App\Models\User;
use App\Models\Users\Doctor;
use App\Models\Users\Paramedis;
use App\Models\Users\Patients;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhysicalExamination extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'paramedis_id',
        'doctor_id',
        'blood_pressure',
        'heart_rate',
        'oxygen_saturation',
        'respiratory_rate',
        'body_temperature',
        'physical_assessment',
        'reason',
        'medical_advice',
        'health_status',
    ];

    // Relasi ke pasien
    public function patient()
    {
        return $this->belongsTo(Patients::class);
    }

    // Relasi ke paramedis
    public function paramedis()
    {
        return $this->belongsTo(Paramedis::class);
    }

    // Relasi ke dokter
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function examiner()
    {
        return $this->belongsTo(User::class, 'examiner_id');
    }
}