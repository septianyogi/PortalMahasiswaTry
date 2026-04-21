<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClassRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'jurusan_id' => ['required'],
            'name' => ['required', 'string', 'max:255'],
            'date' => ['required', 'max:10'],
            'time_start' => ['required'],
            'time_end' => ['required'],
            'dosen_id' => ['required'],
            'quota' => ['required', 'integer'],
            'room' => ['nullable', 'string', 'max:255'],
            'semester' => ['required', 'string', 'max:2'],
        ];
    }
}
