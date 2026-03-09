<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    protected $fillable = [
        'gmail_thread_id',
        'subject',
        'last_message_at'
    ];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
