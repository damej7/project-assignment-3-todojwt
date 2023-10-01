<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TodoService;

class AuthController extends Controller
{
    //

    protected $todoService;

    public function __construct(TodoService $todoService)
    {
        $this->todoService = $todoService;
    }

    public function login(Request $request)
    {
        $credentials = request(['email', 'password']);
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $token = auth()->attempt($credentials);

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    public function refresh()
    {
        return response()->json([
            'access_token' => auth()->refresh(),
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function data()
    {
        return response()->json(auth()->user());
    }

    public function addUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3',
            'password' => 'required|string|min:6',
            'email' => 'required'
        ]);

        $data = [
            'name' => $request->post('name'),
            'password' => $request->post('password'),
            'email' => $request->post('email')
        ];

        $dataSaved = [
            'name' => $data['name'],
            'password' => $data['password'],
            'email' => $data['email'],
            'created_at' => time()
        ];

        $id = $this->todoService->addUser($dataSaved);
        $todo = $this->todoService->getById($id);

        return response()->json($todo);
    }
}
