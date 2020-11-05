<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Content;
use App\Models\Doctor;
use App\Models\Booking;
use App\Models\Schedule;

class Hospital extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'lat',
        'lng',
    ];

    public function contents() {
        return $this->hasMany(Content::class);    
    }

    public function doctors() {
        return $this->hasMany(Doctor::class);    
    }

    public function bookings() {
        return $this->hasMany(Booking::class);    
    }

    public function schedules() {
        return $this->hasMany(Schedule::class);    
    }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
