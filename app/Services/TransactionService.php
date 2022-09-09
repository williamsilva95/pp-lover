<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use App\Repositories\TransactionRepository;
use App\Services\AuthorizationService;
use App\Services\NotificationService;

class TransactionService
{
    protected $user;
    protected $transaction;
    protected $authService;
    protected $notificationService;
    protected $transactionRepository;

    public function __construct()
    {
        $this->user = new User();
        $this->transaction = new Transaction();
        $this->transactionRepository = app(TransactionRepository::class);
        $this->authService = app(AuthorizationService::class);
        $this->notificationService = app(NotificationService::class);
    }
    public function getBalance(int $user_id): Array
    {
        $user = $this->user->find($user_id);
        if($user && $user->balance){
            return ["Balance" => $user->balance];
        }
        throw new \Exception("Erro ao consultar saldo");
    }

    public function transfer(int $payer_id, int $payee_id, float $amount) : Array
    {
        $payer = $this->user->find($payer_id);
        $payee = $this->user->find($payee_id);

       if($payer->type === "PJ"){
            throw new \Exception("Lojistas não podem fazer transferências");
        }

        if ($payer->balance < $amount) {
            throw new \Exception("Saldo insuficiente");
        }

        if(!$this->transactionRepository->debit($payer, $amount)){
            throw new \Exception("Erro ao debitar");
        }

        if(!$this->transactionRepository->deposit($payee, $amount)){
            throw new \Exception("Erro ao depositar");
        }

        if($payer->transfer($payee, $amount) && $this->authService->authorizeTransaction($payer->id) ){
            if($this->notificationService->sendNotice()){
                return ["message" => "Transferência realizada, em breve você recebera a confirmação no seu e-mail"];
            }
            return ["message" => "Transferência realizada com sucesso"];
        }

        throw new \Exception("Erro ao realizar transferência.");
    }
}