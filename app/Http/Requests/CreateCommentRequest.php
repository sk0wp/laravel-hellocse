<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class CreateCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'error' => 'true',
            'message' => 'Create comment failed',
            'errorsList' => $validator->errors()
        ],
        Response::HTTP_BAD_REQUEST));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'content' => ['string', 'required', 'max:65535'],
            'profile_id' => ['exists:profiles,id', 'required'],
            'administrator_id' => [
                'exists:administrators,id',
                'required',
                Rule::unique('comments')->where(function ($query) {
                    return $query->where('administrator_id', $this->input('administrator_id'))
                        ->where('profile_id', $this->input('profile_id'));
                }),
            ],
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
