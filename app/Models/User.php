<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'bio'
    ];

    protected $hidden = ['password', 'remember_token'];
    
    protected $casts = ['email_verified_at' => 'datetime'];

    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }
    
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    
    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = Role::whereName($role)->firstOrFail();
        }
        $this->roles()->syncWithoutDetaching($role);
    }
    
    public function isAdmin()
    {
        return $this->roles()->where('name', 'admin')->exists();
    }
    
    public function isAuthor()
    {
        return $this->roles()->where('name', 'author')->exists();
    }
}