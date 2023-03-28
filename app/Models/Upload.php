<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment_id',
        'filename',
        'url',
        'path', 
        'thumbnail_url',
        'thumbnail_path',
    ];

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}
