<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Hospital;

class HospitalType extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name'
    ];
    
    public function hospital() {
        return $this->hasMany(Hospital::class);    
    }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
