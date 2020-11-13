<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\BookingPatientInformation;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Hospital;

class Booking extends Model
{
    use HasFactory;

    protected $attributes = [
        'message' => "",
    ];

    protected $fillable = [
        'doctor_id',
        'hospital_id',
        'patient_id',
        'message',
        'booking_for',
        'is_active',
        'date',
        'message',
        'booking_code',
        'status'
    ];

    public function hospital() {
        return $this->belongsTo(Hospital::class);    
    }

    public function booking_patient_information() {
        return $this->hasMany(BookingPatientInformation::class);    
    }

    public function patient() {
        return $this->belongsTo(Patient::class);    
    }

    public function doctor() {
        return $this->belongsTo(Doctor::class);    
    }

    public function isActive()
    {
        return (bool) $this->is_active > 0;
    }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
