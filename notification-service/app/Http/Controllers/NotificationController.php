<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\GenericNotification;
use App\Models\InAppNotification;

class NotificationController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'message' => 'required|string',
            'type' => 'required|in:email,in-app',
            'email' => 'sometimes|email',
            'subject' => 'sometimes|string'
        ]);

        $email = $request->email;

        // Ambil email dari User Service jika tidak dikirim
        if (!$email && $request->type === 'email') {
            $response = Http::get("http://user-service/api/users/{$request->user_id}");
            if ($response->successful()) {
                $email = $response->json('email');
            } else {
                return response()->json(['error' => 'User not found'], 404);
            }
        }

        if ($request->type === 'email' && $email) {
            Mail::to($email)->send(new GenericNotification(
                $request->subject ?? 'Library Notification',
                $request->message
            ));
        }

        if ($request->type === 'in-app') {
            InAppNotification::create([
                'user_id' => $request->user_id,
                'message' => $request->message,
            ]);
        }

        return response()->json(['status' => 'Notification sent or stored']);
    }

    public function getUserNotifications($user_id)
    {
        $notifications = InAppNotification::where('user_id', $user_id)
                            ->orderBy('created_at', 'desc')
                            ->get();

        return response()->json([
            'user_id' => $user_id,
            'notifications' => $notifications,
        ]);
    }
}
