<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'job',
        'state',
        'service',
    ];

    // MUTATOR - ACCESSOR
    /**
     * Interact with the listing.
     *
     * @return  \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function setJobAttribute($value)
    {
        $this->attributes['job'] = ucfirst(strtolower($value));
    }

    protected function getJobAttribute($value)
    {
        return ucfirst(strtolower($this->attributes['job']));
    }

    protected function setStateAttribute($value)
    {
        $this->attributes['state'] = ucfirst(strtolower($value));
    }

    protected function getStateAttribute($value)
    {
        return ucfirst(strtolower($this->attributes['state']));
    }

    protected function setServiceAttribute($value)
    {
        $this->attributes['service'] = ucfirst(strtolower($value));
    }

    protected function getServiceAttribute($value)
    {
        return ucfirst(strtolower($this->attributes['service']));
    }
}