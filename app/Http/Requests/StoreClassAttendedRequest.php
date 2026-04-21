<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClassAttendedRequest extends FormRequest
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
            'class_id' => ['required'],
            'stuent_id' => ['required'],
            'attendance' => ['required', 'max:2'],
            'absent' => ['max:2', 'integer'],
            'mid_exam' => ['max:3', 'integer'],
            'final_exam' => ['max:3', 'integer'],
            'final_score' => ['max:3', 'integer'],
           
        ];
    }
}
