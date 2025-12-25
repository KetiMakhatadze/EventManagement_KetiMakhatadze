<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event_id' => 'required|exists:events,id',
            'quantity' => 'required|integer|min:1|max:10',
            'participants' => 'required|array',
            'participants.*.first_name' => 'required|string|max:255',
            'participants.*.last_name' => 'required|string|max:255',
            'participants.*.email' => 'required|email',
            'participants.*.phone' => 'nullable|string|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.required' => 'მიუთითეთ ბილეთების რაოდენობა',
            'participants.required' => 'შეავსეთ მონაწილეთა ინფორმაცია',
            'participants.*.email.email' => 'არასწორი ელ.ფოსტის ფორმატი',
        ];
    }
}