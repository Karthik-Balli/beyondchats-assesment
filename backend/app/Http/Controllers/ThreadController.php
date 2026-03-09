<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    public function index()
    {
        return Thread::with('messages')
            ->orderBy('last_message_at', 'desc')
            ->paginate(20);
    }

    public function show($id)
    {
        return Thread::with('messages.attachments')
            ->findOrFail($id);
    }
}