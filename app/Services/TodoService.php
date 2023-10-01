<?php

namespace App\Services;

use App\Repositories\TodoRepository;
use MongoDB\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\Validator;

class TodoService
{

  protected $todoRepository;

  public function __construct(TodoRepository $todoRepository)
  {
    $this->todoRepository = $todoRepository;
  }

  public function getAll()
  {
    $todo = $this->todoRepository->getAll();

    return $todo;
  }

  // public function store($data): Object
  // {
  //   $validator = Validator::make($data, [
  //     'title' => 'required',
  //   ]);

  //   if ($validator->fails()) {
  //     throw new InvalidArgumentException($validator->errors()->first());
  //   }

  //   $result = $this->todoRepository->store($data);
  //   return $result;
  // }

  public function addTodo(array $data)
  {
    $todo = $this->todoRepository->create($data);
    // return response()->json($todo);
    return $todo;
  }

  public function getById(string $todoId)
  {
    $todo = $this->todoRepository->getById($todoId);
    return $todo;
  }

  public function updateTodo(array $editTodo, array $formData)
  {
    if (isset($formData['title'])) {
      $editTodo['title'] = $formData['title'];
    }

    $id = $this->todoRepository->save($editTodo);
    return $id;
  }

  public function destroyTodoId(string $todoId)
  {
    $existTodo = $this->todoRepository->getById($todoId);
    if ($existTodo) {
      $this->todoRepository->destroyTodoId($todoId);
      return true;
    } else {
      return false;
    }
  }
}
