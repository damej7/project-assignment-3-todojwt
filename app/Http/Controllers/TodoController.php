<?php

namespace App\Http\Controllers;

use App\Services\TodoService;
use Exception;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    //

    protected $todoService;

    public function __construct(TodoService $todoService)
    {
        $this->todoService = $todoService;
    }

    public function getTodoList()
    {
        try {
            $result = $this->todoService->getAll();
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result);
    }

    public function createTodo(Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:3',
        ]);

        $data = [
            'title' => $request->post('title'),
        ];

        $dataSaved = [
            'title' => $data['title'],
            'created_at' => time()
        ];

        $id = $this->todoService->addTodo($dataSaved);
        $todo = $this->todoService->getById($id);

        return response()->json($todo);
    }

    public function deleteTodo(Request $request)
    {
        $request->validate([
            'todo_id' => 'required'
        ]);

        $todoId = $request->todo_id;

        $existTodo = $this->todoService->destroyTodoId($todoId);

        if ($existTodo != false) {
            return response()->json([
                "message" => "Todo dengan ID " . $todoId . " telah di hapus."
            ]);
        } else {
            return response()->json([
                "message" => "Todo ID dengan ID " . $todoId . " tidak di temukan silahkan coba lagi."
            ]);
        }
    }

    public function updateTodo(Request $request)
    {
        $request->validate([
            'todo_id' => 'required|string',
            'title' => 'string',
        ]);

        $todoId = $request->post('todo_id');
        $formData = $request->only('title');
        $todo = $this->todoService->getById($todoId);

        $this->todoService->updateTodo($todo, $formData);

        $todo = $this->todoService->getById($todoId);

        return response()->json([
            "message" => "Todo dengan ID " . $todoId . " berhasil di update dengan title " . $todo['title'] . "."
        ]);
    }
}
