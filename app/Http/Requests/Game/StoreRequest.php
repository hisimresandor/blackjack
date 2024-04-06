<?php

namespace App\Http\Requests\Game;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'player_cards' => ['required', 'array', 'min:2'],
            'dealer_cards' => ['required', 'array', 'size:2'],
            'deck' => ['required', 'array'],
            'bet' => ['required', 'integer', 'min:0'],
        ];
    }
}
