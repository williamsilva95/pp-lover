<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }
    
    /**
     * Transfer funds from one user to another
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function transfer(Request $request)
    {
        if ($request->validated()) {
            try {
                $response = $this->transactionService->transfer($request->payer_id, $request->payee_id, $request->amount);
                return response()->json($response, Response::HTTP_OK);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ], Response::HTTP_BAD_REQUEST);
            }
        }
    }
}
