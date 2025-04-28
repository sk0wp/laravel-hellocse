<?php

namespace App\Http\Requests;

use App\Enums\ProfileStatusEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Enum;

class DeleteOrEditProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'error' => 'true',
            'message' => 'Edit / Delete failed',
            'errorsList' => $validator->errors()
        ]));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['integer', 'required'],
            'firstname' => ['string', 'max:255'],
            'lastname' => ['string', 'max:255'],
            'status' => [new Enum(ProfileStatusEnum::class)],
            'administrator_id' => ['exists:administrators,id'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function prepareForValidation(): void
    {
        $administrator = auth('api')->user();

        $this->merge([
            'administrator_id' => $administrator->id,
        ]);
    }
}
