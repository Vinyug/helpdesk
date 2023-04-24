<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ticket_id',
        'content',
        'editable',
        'time_spent',
    ];


    // RELATIONSHIP
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function uploads()
    {
        return $this->hasMany(Upload::class);
    }

    // MUTATOR - ACCESSOR
    /**
     * Interact with the user.
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
