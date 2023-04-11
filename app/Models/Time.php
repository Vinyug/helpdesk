<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'time_spent',
        'hourly_rate', 
    ];

    // RELATIONSHIP
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
