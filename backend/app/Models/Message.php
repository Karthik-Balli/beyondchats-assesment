<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'thread_id',
        'gmail_message_id',
        'sender',
        'receiver',
        'body_html',
        'sent_at'
    ];

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
}