<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Validation\Rule;

class UserRepository
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function rules()
    {
        return [
            'document' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'type'=> ['required', Rule::in(['PJ', 'PF'])],
        ];
    }

    public $mensages = [

        'email.required' => 'Email não informado.',
        'email.unique' => 'Email ja existente.',
        'document.required' => 'Documento é obrigatório.',
        'document.unique' => 'Documento ja existente.',
        'type.required' => 'Tipo não informado.',

    ];

}