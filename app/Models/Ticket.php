<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'company_id',
        'ticket_number', 
        'subject', 
        'uuid',
        'state',
        'service', 
        'visibility', 
        'hourly_rate',
        'editable',
        'notification_sent',
    ];

    // allow to transmit uuid in URL (instead of id by default)
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }


    // RELATIONSHIP
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


    // MUTATOR - ACCESSOR
    /**
     * Interact with the ticket.
     *
     * @return  \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function setSubjectAttribute($value)
    {
        $this->attributes['subject'] = ucfirst(strtolower($value));
    }

    protected function getSubjectAttribute($value)
    {
        return ucfirst(strtolower($this->attributes['subject']));
    }
}
