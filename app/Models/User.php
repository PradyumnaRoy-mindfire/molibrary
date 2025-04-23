<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function librarian()
    {
        return $this->hasOne(Librarian::class);
    }
    public function libraries()
    {
        return $this->hasMany(Library::class, 'admin_id');
    }
    public function borrows()
    {
        return $this->hasMany(Borrow::class, 'users_id');
    }
    public function membership()
    {
        return $this->hasOne(Membership::class, 'user_id')->latestOfMany();
    }
    public function fines()
    {
        return $this->hasManyThrough(Fine::class,Borrow::class,'users_id','borrow_id');
    }
    



    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
