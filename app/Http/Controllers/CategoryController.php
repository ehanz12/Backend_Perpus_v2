<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{

    public function index()
    {
        try {
            $category = Category::select('name', 'id')->get();

            if(!$category) {
                return response()->json(["message" => "category not found"], 401);
            }

            return response()->json([
                "message" => "category founds",
                "data" => $category
            ], 200);
        } catch (\Exception $th) {
            return response()->json([
                "message" => "category not found",
                "data" => null,
                "error" => $th->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "name" => "string|required|min:3|max:100"
        ]);
        DB::beginTransaction();
        try {
            $category = new Category();
            $category->name = $data['name'];
            $category->save();

            DB::commit();

            return response()->json([
                "message" => "category created successfully",
                "data" => $category
            ], 201);
        } catch (\Exception $th) {
            return response()->json([
                "message" => "error create category failed",
                "data" => null,
                "error" => $th->getMessage()
            ], 500);
        }   
    }
    
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            "name" => "string|min:3|max:100|required"
        ]);

        DB::beginTransaction();
        try {
            $category = Category::findOrFail($id);
            $category->name = $data['name'];
            $category->save();

            DB::commit();
            return response()->json([
                "message" => "category successfuly to update",
                "data" => $category
            ], 200);
        } catch (\Exception $th) {
            return response()->json([
                "message" => "error failed update category",
                "error" => $th->getMessage(),
                "data" => null
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();

            return response()->json([
                "message" => "deleted category successfully",
                "data" => null
            ], 200);        
        } catch (\Exception $th) {
            return response()->json([
                "message" => "deleted category failed",
                "error" => $th->getMessage(),
                "data" => null
            ], 500);
        }
    }
}
