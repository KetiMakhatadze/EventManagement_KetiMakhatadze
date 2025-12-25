<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'total_seats' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
        ];

        if ($this->isMethod('post')) {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg|max:2048';
        } else {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg|max:2048';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'title.required' => 'სათაური სავალდებულოა',
            'start_date.after' => 'დაწყების თარიღი უნდა იყოს მომავალში',
            'end_date.after' => 'დასრულების თარიღი უნდა იყოს დაწყებიდან გვიან',
            'categories.required' => 'აირჩიეთ მინიმუმ ერთი კატეგორია',
        ];
    }
}