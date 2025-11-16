<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    public function borrow(Request $request)
    {
        try {
            // Logic for borrowing a book
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'borrowed_at' => 'nullable|date',
            'due_date' => 'nullable|date|after:borrowed_at',
        ]);
        DB::beginTransaction();
        $book = Book::find($request->book_id);
        // Additional logic for borrowing a book
        if($book->stock <= 0){
            return response()->json([
                "message" => "Book is out of stock!",
                "data" => null
            ], 400);
        }

        $borrowed_at = $request->borrowed_at ?? now();
        $due_date = $request->due_date ?? now()->addWeeks(2);

        // Create borrowing record
        $borrowing = Borrowing::create([
            'user_id' => $request->user_id,
            'book_id' => $request->book_id,
            'borrowed_at' => $borrowed_at,
            'due_at' => $due_date,
            'status' => 'borrowed',
        ]);

        // Decrease book stock
        $book->decrement('stock');
        DB::commit();
        return response()->json([
            "message" => "Books successfuly borrow",
            "data" => $borrowing
        ], 201);

        } catch (\Exception $th) {
            return response()->json([
                "message" => "error borrow",
                "data" => null,
                "error" => $th->getMessage()
            ], 500);  
        }
        
    }

    public function returnBook($id)
    {
        $borrowing = Borrowing::find($id);
        $book = Book::find($borrowing->book_id);
        DB::beginTransaction();
        try {
            $borrowing->returned_at = now();
            if (now()->greaterThan($borrowing->due_at)) {
                $daysOverdue = now()->diffInDays($borrowing->due_at);
                $borrowing->fine = $daysOverdue * 1000; // Example fine
                $borrowing->status = 'overdue';
            } else {
                $borrowing->status = 'returned';
                $borrowing->fine = 0;
            }
            $borrowing->save();
            // Increase book stock
            $book->increment('stock');
            DB::commit();

            return response()->json([
                "message" => "Book successfuly reaturned",
                "data" => [
                    "borrowing" => $borrowing
                ]
            ], 201);
        } catch (\Exception $th) {
            return response()->json([
                "message" => "Error returned",
                "data" => null,
                "error" => $th->getMessage()
            ], 500);
        }
    }
}
