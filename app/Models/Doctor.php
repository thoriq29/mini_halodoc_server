<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Spesialist;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'bio',
        'name',
        'image_url',
        'credential',
        'academic_affiliation',
        'spesialist_id',
        'user_id'
    ];

    protected $appends = ['type'];

    public function getTypeAttribute(){
        return "doctor";
    }

    public function schedules() {
        return $this->belongsToMany(Schedule::class,'doctor_schedules');
    }

    public function bookings() {
        return $this->hasMany(Booking::class);    
    }

    public function spesialist() {
        return $this->belongsTo(Spesialist::class);    
    }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

}
