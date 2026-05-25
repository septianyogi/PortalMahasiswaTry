<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonalInformationRequest extends FormRequest
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
        return [
            'dob' => 'nullable|date',
            'country' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'subdistrict' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'dob.date' => 'Tanggal lahir tidak valid',
        ];
    }
}
