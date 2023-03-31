<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'firstname' => ['string', 'max:50'],
            'lastname' => ['string', 'max:50'],
            'email' => ['email', 'max:100', Rule::unique(User::class)->ignore($this->user()->id)],
        ];
    }
}
