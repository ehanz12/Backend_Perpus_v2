<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Requests\BookRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BooksController extends Controller
{

    public function index()
    {
        try {
            $books = Book::with('category:id,name')->select('id', 'title', 'author', 'category_id', 'description', 'image', 'stock', 'pages', 'price', 'published_at')->get();
            return response()->json([
                "message" => "Books this found !",
                "data" => $books
            ], 200);
        } catch (\Exception $th) {
            return response()->json([
                "message" => "error ",
                "data" => null,
                "error" => $th->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $book = Book::with('category:id,name')->select('id', 'title', 'author', 'category_id', 'description', 'image', 'stock', 'pages', 'price', 'published_at')->find($id);

            if(!$book) {
                return response()->json([
                    "message" => "book not found !",
                    "data" => null
                ], 400);
            }

            return response()->json([
                "message" => "book found !",
                "data" => $book
            ], 200);
        } catch (\Exception $th) {
            return response()->json([
                "message" => "error ",
                "data" => null,
                "error" => $th->getMessage()
            ], 500);
        }
    }

    public function update(BookRequest $request, $id)
    {
        $data = $request->validated();
        $book = Book::Find($id);
        DB::beginTransaction();
        
        try {
        $imagePath = null;
        if ($request->hasFile('image')) {
                // Hapus cover lama
                if ($book->image && Storage::disk('public')->exists($book->image)) {
                    Storage::disk('public')->delete($book->image);
                }
                // Upload cover baru
                $imagePath = $request->file('image')->store('image', 'public');
            }

            if(!$book) {
                return response()->json([
                    "message" => "book not found !",
                    "data" => null
                ], 400);
            }

            $book->title = $data['title'];
            $book->author = $data['author'];
            $book->category_id = $data['category_id'];
            $book->description = $data['description'];
            if ($imagePath) {
                $book->image = $imagePath;
            }
            $book->stock = $data['stock'];
            $book->pages = $data['pages'];
            $book->price = $data['price'];
            $book->published_at = $data['published_at'];

            $book->save();
            
            DB::commit();

            return response()->json([
                "message" => "updated book successfully",
                "data" => $book,
            ], 200);
        } catch (\Exception $th) {
            return response()->json([
                "message" => "error failed updated book!",
                "data" => null,
                "error" => $th->getMessage()
            ], 500);
        }
    }

    public function store(BookRequest $request)
    {
        $data = $request->validated();
        DB::beginTransaction();

        $imagePath = null;
        if ($request->file('image')) {
            $file = $request->file('image');

            $file->getClientOriginalName();
            $imagePath = $file->store('image', 'public');
        }

        try {
            $book = new Book();
            $book->title = $data['title'];
            $book->author = $data['author'];
            $book->category_id = $data['category_id'];
            $book->description = $data['description'];
            $book->image = $imagePath;
            $book->stock = $data['stock'];
            $book->pages = $data['pages'];
            $book->price = $data['price'];
            $book->published_at = $data['published_at'];

            $book->save();
            DB::commit();

            return response()->json([
                "message" => "created book successfully",
                "data" => $book,
            ], 201);
        } catch (\Exception $th) {
            return response()->json([
                "message" => "error failed created book!",
                "data" => null,
                "error" => $th->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $book = Book::Find($id);
        try {
            if(!$book) {
                return response()->json([
                    "message" => "book not found !",
                    "data" => null
                ], 400);
            }
            $book->delete();

            return response()->json([
                "message" => "book found !",
                "data" => null
            ], 200);    
        } catch (\Exception $th) {
            return response()->json([
                "message" => "error ",
                "data" => null,
                "error" => $th->getMessage()
            ], 200);
        }
    }
}
