<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DataBorrowStoreRequest extends FormRequest
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
            'name_borrower' => 'required|string|max:60|regex:/^[a-zA-Z\s]+$/',
            'class' => 'required|in:X PPLG,X PMN,X HTL,XI PPLG,XI PMN,XI HTL,XI TJKT',
            'no_hp' => 'required|regex:/^[0-9]{10,13}$/',
            'gender' => 'required|in:Male,Female',
        ];
    }
}
