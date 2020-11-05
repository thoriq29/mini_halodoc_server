<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Doctor;
use App\Models\Schedule;

class DoctorSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'schedule_id',
    ];

    public function doctor() {
        return $this->belongsTo(Doctor::class);    
    }

    public function schedule() {
        return $this->belongsTo(Schedule::class);    
    }

    protected $hidden = ['pivot'];
}
