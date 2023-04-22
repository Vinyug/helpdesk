<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'rate', 
        'content', 
        'visibility', 
        'show', 
    ];


    // RELATIONSHIP
    public function users()
    {
        return $this->belongsTo(User::class);
    }


    // MUTATOR - ACCESSOR
    /**
     * Interact with the review.
     *
     * @return  \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function setContentAttribute($value)
    {
        $this->attributes['content'] = ucfirst($value);
    }

    protected function getContentAttribute($value)
    {
        return ucfirst($this->attributes['content']);
    }
}
