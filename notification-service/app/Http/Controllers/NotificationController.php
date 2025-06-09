<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    // Send borrowing notification
    public function sendBorrowingNotification(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'book_title' => 'required',
            'action' => 'required|in:borrowed,returned'
        ]);

        $action = $request->action;
        $title = $action == 'borrowed' ? 'Book Borrowed' : 'Book Returned';
        $message = "You have {$action} the book: {$request->book_title}";

        $notification = Notification::create([
            'user_id' => $request->user_id,
            'type' => 'borrowing',
            'title' => $title,
            'message' => $message,
            'status' => 'sent'
        ]);

        // Optional: Send email (simple version)
        $this->sendEmail($request->user_id, $title, $message);

        return response()->json([
            'success' => true,
            'data' => $notification,
            'message' => 'Notification sent successfully'
        ]);
    }

    // Send reminder notification
    public function sendReminderNotification(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'book_title' => 'required',
            'due_date' => 'required'
        ]);

        $title = 'Book Due Reminder';
        $message = "Your book '{$request->book_title}' is due on {$request->due_date}. Please return it on time.";

        $notification = Notification::create([
            'user_id' => $request->user_id,
            'type' => 'reminder',
            'title' => $title,
            'message' => $message,
            'status' => 'sent'
        ]);

        $this->sendEmail($request->user_id, $title, $message);

        return response()->json([
            'success' => true,
            'data' => $notification,
            'message' => 'Reminder sent successfully'
        ]);
    }

    // Send fine notification
    public function sendFineNotification(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'book_title' => 'required',
            'fine_amount' => 'required|numeric'
        ]);

        $title = 'Late Return Fine';
        $message = "You have a fine of \${$request->fine_amount} for late return of '{$request->book_title}'. Please pay the fine.";

        $notification = Notification::create([
            'user_id' => $request->user_id,
            'type' => 'fine',
            'title' => $title,
            'message' => $message,
            'status' => 'sent'
        ]);

        $this->sendEmail($request->user_id, $title, $message);

        return response()->json([
            'success' => true,
            'data' => $notification,
            'message' => 'Fine notification sent successfully'
        ]);
    }

    // Send availability notification
    public function sendAvailabilityNotification(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'book_title' => 'required'
        ]);

        $title = 'Book Available';
        $message = "The book '{$request->book_title}' is now available for borrowing.";

        $notification = Notification::create([
            'user_id' => $request->user_id,
            'type' => 'availability',
            'title' => $title,
            'message' => $message,
            'status' => 'sent'
        ]);

        $this->sendEmail($request->user_id, $title, $message);

        return response()->json([
            'success' => true,
            'data' => $notification,
            'message' => 'Availability notification sent successfully'
        ]);
    }

    // Get user notifications
    public function getUserNotifications($userId)
    {
        $notifications = Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
    }

    // Get all notifications (admin)
    public function getAllNotifications()
    {
        $notifications = Notification::orderBy('created_at', 'desc')
            ->paginate(50);

        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
    }

    // Simple email sending (optional)
    private function sendEmail($userId, $title, $message)
    {
        // Very basic email sending - you can skip this if not needed
        try {
            // You would need to get user email from user service or database
            // For now, just log it
            Log::info("Email notification for user {$userId}: {$title} - {$message}");
            
            // If you want to send real emails, uncomment below and configure mail settings
            /*
            Mail::raw($message, function ($mail) use ($title) {
                $mail->to('user@example.com') // Get from user service
                     ->subject($title);
            });
            */
        } catch (\Exception $e) {
            Log::error("Failed to send email: " . $e->getMessage());
        }
    }
}