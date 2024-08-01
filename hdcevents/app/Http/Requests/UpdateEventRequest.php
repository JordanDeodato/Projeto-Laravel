<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:100|min:3',
            'date' => 'required|date',
            'city' => 'required|string|max:100|min:3',
            'private' => 'required|boolean',
            'description' => 'required|string|max:500|min:3',
            'items.*' => 'string',
            'image' => 'nullable|file|image|mimes:png,jpg,jpeg,svg,webp|max:1024',
        ];
    }
}
