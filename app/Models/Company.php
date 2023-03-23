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
        'present',
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
     * Set the name attribute and capitalize the first letter.
     *
     * @param  string  $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst($value);
    }

}