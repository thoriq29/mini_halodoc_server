<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Auth\Role;
use App\Models\User;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug'
    ];

    public function roles() {
        return $this->belongsToMany(Role::class,'roles_permissions');        
    }
     
    public function users() {
        return $this->belongsToMany(User::class,'users_permissions');        
    }
}
