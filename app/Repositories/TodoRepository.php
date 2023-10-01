<?php

namespace App\Repositories;

use App\Helpers\MongoModel;
use App\Models\Todo;

class TodoRepository
{
  private MongoModel $todos;
  private MongoModel $users;

  public function __construct()
  {
    $this->todos = new MongoModel('todos');
    $this->users = new MongoModel('users');
  }

  public function getAll()
  {
    $todos = $this->todos->get([]);
    return $todos;
  }

  public function create(array $data)
  {
    $dataSaved = [
      'title' => $data['title'],
      'created_at' => time()
    ];

    $id = $this->todos->save($dataSaved);
    return $id;
  }

  public function createUser(array $data)
  {
    $dataSaved = [
      'name' => $data['name'],
      'password' => $data['password'],
      'email' => $data['email'],
      'created_at' => time()
    ];

    $id = $this->users->save($dataSaved);
    return $id;
  }

  public function save(array $editedData)
  {
    $id = $this->todos->save($editedData);
    return $id;
  }

  public function getById(string $id)
  {
    $todo = $this->todos->find(['_id' => $id]);
    return $todo;
  }

  public function getEmail(string $email)
  {
    $user = $this->users->find(['email' => $email]);
    return $user;
  }

  public function destroyTodoId(string $id)
  {
    $todo = $this->todos->deleteQuery(["_id" => $id]);
    return $todo;
  }
}
