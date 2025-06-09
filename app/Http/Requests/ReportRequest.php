<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
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
            'time_occurred' => 'required',
            'date_occurred' => 'required',
            'time_arrival_on_site' => 'required',
            'landmark' => 'required|string',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'barangay_id' => 'required',
            'actions_id' => 'required',
            'description' => 'nullable|string',
            'urgency_id' => 'required',
            'source_id' => 'required',
            'incident_id' => 'required',
            'assistance_id' => 'required',
        ];
    }
}
