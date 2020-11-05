<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Hospital;

class Booking extends Model
{
    use HasFactory;

    public function hospital() {
        return $this->belongsTo(Hospital::class);    
    }

    public function patient() {
        return $this->belongsTo(Patient::class);    
    }

    public function doctor() {
        return $this->belongsTo(Doctor::class);    
    }
}
