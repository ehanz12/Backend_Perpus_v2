<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "title" => "required|min:3|max:100",
            "author" => "required|min:3|max:100",
            "category_id" => "required",
            "image" => "nullable",
            "description" => "nullable",
            "pages" => "required|integer|min:1",
            "stock" => "required|integer|min:0",
            "price" => "required|integer|min:0",
            "published_at" => "required"
        ];
    }
}
