<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Auth\Permission;
use App\Models\User;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug'
    ];

    public function permissions() {
        return $this->belongsToMany(Permission::class,'roles_permissions');    
    }
    
    public function users() {
        return $this->belongsToMany(User::class,'users_roles');
    }
}
