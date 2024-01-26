<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
  /*
   * @param int $id
   * @return User
   * */
  public function find(int $id): User
  {
    return User::find($id);
  }

  /*
   * @param array<mixed> $data
   * @return User
   * */
  public function create(array $data): User
  {
    return User::query()->create([
      'name' => $data['name'],
      'email' => $data['email'],
      'password' => Hash::make($data['password']),
    ]);
  }

}
