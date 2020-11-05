<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Doctor;

class Spesialist extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'description'
    ];

    public function doctors() {
        return $this->hasMany(Doctor::class);    
    }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
