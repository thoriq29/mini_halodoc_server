<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'short_desc',
        'content_text',
        'image_url',
        'hasRead',
        'tag',
        'date'
    ];

    public function user() {
        return $this->belongsTo(User::class);    
    }
}
