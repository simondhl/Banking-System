<?php

namespace App\Http\Requests;

use App\Rules\NoHtml;
use Illuminate\Foundation\Http\FormRequest;

class TransactionFormRequest extends FormRequest
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
        return match($this->route()->getActionMethod()) {
        
          'deposit_or_withdrawal' => [
            'transaction_type' => 'required|string',
            'amount' => 'required',
            'account_number' => ['required', 'string','exists:accounts,account_number', 'max:255', new NoHtml]
          ],
          'transfer' => [
            'amount' => 'required',
            'account_number_sender' => ['required', 'string','exists:accounts,account_number', 'max:255', new NoHtml],
            'account_number_reciever' => ['required', 'string','exists:accounts,account_number', 'max:255', new NoHtml]
          ],

          default => [],
        };
    }
}
