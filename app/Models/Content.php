<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\ContentCategory;
use App\Models\Hospital;

class Content extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'desc',
        'image_url',
        'date',
        'hospital_id',
        'content_category_id'
    ];

    protected $appends = ['type'];
    public function getTypeAttribute(){
        return "content";
    }

    public function category() {
        return $this->belongsTo(ContentCategory::class);    
    }

    public function hospital() {
        return $this->belongsTo(Hospital::class);    
    }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
