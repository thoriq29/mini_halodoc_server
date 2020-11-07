<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Hospital;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'desc',
        'name',
        'tagline',
        'motto',
        'date_of_establishment'
    ];
    
    public function hospitals() {
        return $this->hasMany(Hospital::class);    
    }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
