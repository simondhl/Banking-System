<?php

namespace App\Http\Requests;

use App\Rules\NoHtml;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserFormRequest extends FormRequest
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
        return match ($this->route()->getActionMethod()) {

            'create_employee' => [
                'phone_number' => ['required', 'string', 'unique:users', new NoHtml],
                'user_number' => ['required', 'string', 'unique:users', new NoHtml],
                'national_number' => ['required', 'string','unique:users', new NoHtml],
                'password' => ['required', 'confirmed', Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()],
                'first_name' => ['required', 'string', 'max:255', new NoHtml],
                'last_name' => ['required', 'string', 'max:255', new NoHtml],
                'location' => ['required', 'string', 'max:255', new NoHtml],
            ],

            default => [],
        };
    }
}
