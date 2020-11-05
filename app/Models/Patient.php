<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Booking;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address',
        'phone',
        'sex'
    ];

    public function user() {
        return $this->belongsTo(User::class);    
    }
    
    public function bookings() {
        return $this->hasMany(Booking::class);    
    }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
