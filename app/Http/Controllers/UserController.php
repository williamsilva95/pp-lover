<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    
    protected $user;
    protected $userRepository;
    protected $transaction;

    public function __construct(UserRepository $userRepository, TransactionService $transaction, User $user )
    {
        $this->user = $user;
        $this->userRepository = $userRepository;
        $this->transaction = $transaction;
    }

    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->user->paginate();
        return response()->json([
            'error' => 'Nenhum usuário encontrado'
        ], Response::HTTP_NOT_FOUND);
        if($users){
            return response()->json([
                'data' => $users
            ], Response::HTTP_OK);
        }
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $this->user;
        $user->name = $request->name;
        $user->document = $request->document;
        $user->type = $request->type;
        $user->password = Hash::make($request->password);
        $user->email = $request->email;

        $validation = $this->validator($user, $this->userRepository->rules(), $this->userRepository->mensages);

        if($validation->fails()){
            return ["success" => false, "messages" => $validation->errors($validation)];
        }

        if($user->save()){
            return response()->json([
                'message' => 'Usuário cadastrado com sucesso',
                'user' => $user
            ], Response::HTTP_CREATED);
        }
        return response()->json([
            'error' => 'Erro ao cadastrar usuário'
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * Display the user resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $user = $this->user->find($id);
        if(!$user){
            return response()->json([
                'error' => 'Usuário não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            $user
        ], Response::HTTP_OK);
    }
    
    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = $this->user->find($request->id);
        if(!$user){
            return response()->json([
                'error' => 'Usuário não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json([
            'message' => 'Usuário atualizado com sucesso',
            $user
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $user = $this->user->find($id);
        if(!$user){
            return response()->json([
                'error' => 'Usuário não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }
        $user->delete();
        return response()->json([
            'message' => 'Usuário deletado com sucesso'
        ], Response::HTTP_OK);
    }

    /**
     * Return user balance
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getBalance(Request $request)
    {
        try {
            $response = $this->transaction->getBalance($request->id);
            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
