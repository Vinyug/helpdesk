<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'zip_code',
        'siret',
        'code_ape',
        'phone',
        'email',
        'uuid',
        'active',
    ];

    // allow to transmit uuid in URL (instead of id by default)
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }


    // RELATIONSHIP
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }


    // MUTATOR - ACCESSOR
    /**
     * Interact with the company.
     *
     * @return  \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst(strtolower($value));
    }

    protected function getNameAttribute($value)
    {
        return ucfirst(strtolower($this->attributes['name']));
    }

    protected function setCityAttribute($value)
    {
        $this->attributes['city'] = strtoupper($value);
    }

    protected function getCityAttribute($value)
    {
        return strtoupper($this->attributes['city']);
    }

    protected function setCodeApeAttribute($value)
    {
        $this->attributes['code_ape'] = strtoupper($value);
    }

    protected function getCodeApeAttribute($value)
    {
        return strtoupper($this->attributes['code_ape']);
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
