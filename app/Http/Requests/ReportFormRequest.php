<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportFormRequest extends FormRequest
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

            'get_report_by_date' => [
                'start_date' => 'required|date|before_or_equal:today',
                'end_date'   => 'required|date|before_or_equal:today|after_or_equal:start_date',
            ],
            default => [],
        };
    }
}
