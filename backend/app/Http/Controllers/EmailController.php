<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EmailSyncService;
use Illuminate\Support\Facades\Auth;

class EmailController extends Controller
{
    protected $syncService;

    public function __construct(EmailSyncService $syncService)
    {
        $this->syncService = $syncService;
    }

    public function sync(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'error' => 'User not authenticated'
            ], 401); 
        }

        $accessToken = $user->access_token;

        if (!$accessToken) {
            return response()->json([
                'error' => 'No access token found. Please re-authenticate with Google.'
            ], 401);
        }

        try {
            $this->syncService->syncEmails($accessToken);

            return response()->json([
                'message' => 'Emails synced successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to sync emails: ' . $e->getMessage()
            ], 500);
        }
    }
}