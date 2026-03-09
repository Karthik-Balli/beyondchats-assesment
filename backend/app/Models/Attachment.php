<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
        'message_id',
        'filename',
        'mime_type',
        'file_url'
    ];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}