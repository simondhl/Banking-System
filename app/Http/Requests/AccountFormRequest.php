<?php

namespace App\Http\Requests;

use App\Rules\NoHtml;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class AccountFormRequest extends FormRequest
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

            'create_account' => [
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
                'balance' => [
                    'required',
                    'numeric',
                    'gt:0',
                    'decimal:0,2',
                    new NoHtml
                ],
                'location' => ['required', 'string', 'max:255', new NoHtml],
                'account_type' => ['required', 'string', 'max:255', 'exists:account_types,type_name', new NoHtml],
                'account_hierarchy' => ['required', 'string', 'max:255', 'exists:account_hierarchies,hierarchy_name', new NoHtml],
                'parent_account_number' => ['string', 'max:255', 'exists:accounts,account_number', new NoHtml],
            ],
            'search_account' => [
                'account_number' => ['required', 'string']
            ],
            'update_account' => [
                'account_status' =>  ['string', 'max:255', 'exists:account_statuses,status'],
                'parent_account_number' => ['string', 'max:255', 'exists:accounts,account_number', new NoHtml]
            ],
            default => [],
        };
    }
}
