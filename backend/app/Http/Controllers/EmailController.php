<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EmailSyncService;

class EmailController extends Controller
{
    protected $syncService;

    public function __construct(EmailSyncService $syncService)
    {
        $this->syncService = $syncService;
    }

    public function sync(Request $request)
    {
        $days = $request->input('days', 7);

        $accessToken = auth()->user()->access_token ?? null;

        if (!$accessToken) {
            return response()->json([
                'error' => 'User not authenticated'
            ], 401);
        }

        $this->syncService->syncEmails($accessToken);

        return response()->json([
            'message' => 'Emails synced successfully'
        ]);
    }
}