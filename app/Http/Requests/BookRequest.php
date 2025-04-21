<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules =  [
            'title' => 'required|string|max:255',
            'edition' => 'nullable|integer|min:1',
            'published_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'total_copies' => 'required|integer|min:0',
            'has_ebook' => 'required|boolean',
            'has_paperbook' => 'required|boolean',
            'description' => 'required|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'ebook_path' => 'nullable|file|mimes:pdf,epub,mobi|max:10240',
            'preview_content_path' => 'nullable|file|mimes:pdf|max:5120',
        ];

        // for updattiion
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $book = $this->route('book'); // Get the book being updated
            $rules['isbn'] = [
                'required', 
                'string', 
                'max:20',
                Rule::unique('books')->ignore($book->id)
            ];
            $rules['image'] = 'nullable|mimes:jpeg,png,jpg,gif|max:2048'; 
        } else {
            // For creating new books
            $rules['isbn'] = 'required|string|max:20|unique:books';
            $rules['image'] = 'required|image|max:2048'; 
        }
        
        // if a  new author added
        if ($this->author_id === 'new-author') {
            $rules['new_author_name'] = 'required|string|max:255';
        }
        
        return $rules;
    }
}
