<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrowing;
use Illuminate\Support\Facades\Http;

class BorrowingController extends Controller
{
    //
    public function borrow(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'user_name' => 'required|string',
            'user_email' => 'required|email',
            'book_id' => 'required|integer',
        ]);

        $bookId = $request->book_id;

        // Fetch book data from external API
        $bookResponse = Http::get("http://localhost:8081/api/books/{$bookId}");

        if ($bookResponse->failed()) {
            return response()->json(['message' => 'Failed to fetch book data.'], 500);
        }

        $book = $bookResponse->json();

        // Check if book is available (Assuming the book has a 'status' or 'available' field)
        if (isset($book['status']) && $book['status'] !== 'available') {
            return response()->json(['message' => 'Book is not available for borrowing.'], 400);
        }

        $borrowedDate = now();
        $dueDate = now()->addDays(7); // Default 7 days borrowing

        // Create borrowing record
        $borrowing = Borrowing::create([
            'user_id'       => $request->user_id,
            'user_name'     => $request->user_name,
            'user_email'    => $request->user_email,
            'book_id'       => $book['id'],
            'isbn'          => $book['isbn'] ?? '',
            'book_title'    => $book['title'] ?? '',
            'borrowed_date' => $borrowedDate,
            'due_date'      => $dueDate,
            'status'        => 'borrowed',
        ]);

        // (Optional) Update book availability in external API
        Http::put("http://localhost:8081/api/books/{$bookId}", [
            'status' => 'borrowed'
        ]);

        return response()->json([
            'message' => 'Book borrowed successfully.',
            'borrowing' => $borrowing
        ], 201);
    }

    public function return($id)
    {
        $borrowing = Borrowing::find($id);

        if (!$borrowing) {
            return response()->json(['message' => 'Borrowing record not found.'], 404);
        }

        if ($borrowing->status === 'returned') {
            return response()->json(['message' => 'Book already returned.'], 400);
        }

        $borrowing->returned_date = now();

        // Fine calculation if overdue
        $fine = $borrowing->calculateFine();
        $borrowing->fine_amount = $fine;
        $borrowing->status = 'returned';
        $borrowing->save();

        // Update external book API (optional)
        Http::put("http://localhost:8081/api/books/{$borrowing->book_id}", [
            'status' => 'available'
        ]);

        return response()->json([
            'message' => 'Book returned successfully.',
            'fine' => $fine,
            'borrowing' => $borrowing
        ]);
    }

    public function extend(Request $request, $id)
    {
        $request->validate([
            'additional_days' => 'required|integer|min:1|max:14' // example: max 14-day extension
        ]);

        $borrowing = Borrowing::find($id);

        if (!$borrowing) {
            return response()->json(['message' => 'Borrowing record not found.'], 404);
        }

        if ($borrowing->status === 'returned') {
            return response()->json(['message' => 'Cannot extend a returned book.'], 400);
        }

        if ($borrowing->due_date < now()) {
            return response()->json(['message' => 'Cannot extend an overdue borrowing. Please return the book.'], 400);
        }

        if ($borrowing->extension_count >= 2) {
            return response()->json(['message' => 'Maximum extensions reached.'], 400);
        }

        // Extend the due date
        $borrowing->due_date = $borrowing->due_date->addDays($request->additional_days);
        $borrowing->extension_count += 1;
        $borrowing->save();

        return response()->json([
            'message' => 'Borrowing extended successfully.',
            'new_due_date' => $borrowing->due_date->toDateString(),
            'borrowing' => $borrowing
        ]);
    }


}
