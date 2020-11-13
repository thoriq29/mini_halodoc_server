<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Booking;

class BookingPatientInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'name', 
        'status',
        'sex'
    ];

    public function booking() {
        return $this->belongsTo(Booking::class);    
    }
}
