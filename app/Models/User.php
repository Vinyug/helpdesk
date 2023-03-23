<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'job',
        'firstname',
        'lastname',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    // RELATIONSHIP 
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


    // MUTATOR - ACCESSOR
    /**
     * Interact with the user.
     *
     * @return  \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function setFirstnameAttribute($value)
    {
        $this->attributes['firstname'] = ucfirst(strtolower($value));
    }

    protected function getFirstnameAttribute($value)
    {
        return ucfirst(strtolower($this->attributes['firstname']));
    }

    protected function setLastnameAttribute($value)
    {
        $this->attributes['lastname'] = strtoupper($value);
    }

    protected function getLastnameAttribute($value)
    {
        return strtoupper($this->attributes['lastname']);
    }

    protected function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    protected function getEmailAttribute($value)
    {
        return strtolower($this->attributes['email']);
    }
}
