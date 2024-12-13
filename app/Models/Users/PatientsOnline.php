<?php

namespace App\Models\Users;

use App\Models\User;
use App\Models\Payments\PaymentOnline;
use Illuminate\Database\Eloquent\Model;
use App\Models\Clinic\PhysicalExaminationOnline;
use App\Models\Screenings\ScreeningOnlineAnswers;
use App\Models\Screenings\ScreeningOnlineQuestions;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PatientsOnline extends Model
{
    use HasFactory;

    protected $table = 'patients_online';
    protected $fillable = ['user_id', 'images_ktp', 'nik', 'name', 'age', 'gender', 'email','contact', 'screening_status', 'payment_status', 'health_status', 'health_check_status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function answers()
    {
        return $this->hasMany(ScreeningOnlineAnswers::class, 'patient_id');
    }

    public function payment()
    {
        return $this->hasOne(PaymentOnline::class, 'patient_id', 'id');
    }

    public function patient()
    {
        return $this->belongsTo(PatientsOnline::class, 'patient_id', 'id');
    }

    public function result()
    {
        return $this->hasMany(PhysicalExaminationOnline::class, 'patient_id');
    }

    public function question()
    {
        return $this->hasMany(ScreeningOnlineQuestions::class, 'patient_id');
    }

    
}
