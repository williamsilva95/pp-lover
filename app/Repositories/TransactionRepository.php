<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Models\User;

class TransactionRepository
{
    protected $transaction;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rulesTransfer()
    {
        return [
            "payer_id" => "required|numeric|exists:users,id",
            "payee_id" => "required|numeric|exists:users,id",
            "amount" => "required|numeric|min:1"
        ];
    }

    public $mensagesTransfer = [

        'payer_id.required' => 'O id do usuário é obrigatório.',
        'payer_id.numeric' => 'O id do usuário precisa ser numérico',
        'payee_id.required' => 'O id do usuário é obrigatório.',
        'payee_id.numeric' => 'O id do usuário precisa ser numérico',
        'amount.required' => 'Valor não informado.',
        'amount.numeric' => 'O valor precisa ser um número positivo',

    ];

    /**
     * Deposit for a user
     */
    public function deposit(User $user, $amount)
    {
        $user->balance += $amount;
        return $user->update();
    }

    /**
     * Debit for a user.
     */
    public function debit(User $user, $amount)
    {
        $user->balance -= $amount;
        return $user->update();
    }

}