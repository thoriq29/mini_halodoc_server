<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Content;

class ContentCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name'
    ];
    
    public function contents() {
        return $this->hasMany(Content::class);    
    }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
