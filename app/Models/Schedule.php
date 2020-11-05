<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Hospital;
use App\Models\Doctor;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id',
        'day',
        'start_at',
        'end_at'
    ];

    public function hospital() {
        return $this->belongsTo(Hospital::class);    
    }

    public function doctors() {
        return $this->belongsToMany(Doctor::class,'doctor_schedules');
    }

    protected $hidden = [
        'pivot',
        'created_at',
        'updated_at',
    ];
}
