<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
  protected UserRepository $userRepository;

  public function __construct(UserRepository $userRepository)
  {
    $this->middleware('throttle:3,1')->only('login');
    $this->userRepository = $userRepository;
  }

  /*
   * @param LoginRequest $request
   * @return UserResource
   * */
  public function login(LoginRequest $request): UserResource
  {
    $request->authenticate();

    return UserResource::make($request->user());
  }

  /*
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   * */
  public function logout(Request $request): JsonResponse
  {
    Auth::guard('web')->logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    auth()->user()->tokens()->delete();

    return response()->json([
      'message' => 'Logged out'
    ]);
  }

  /*
   * @param RegisterRequest $request
   * @return UserResource
   * */
  public function register(RegisterRequest $request): UserResource
  {
    $data = $request->validated();

    $user = $this->userRepository->create($data);

    event(new Registered($user));

    $token = $user->createToken('api-token');

    return UserResource::make($user);
  }

  /*
   * @param Request $request
   * @return UserResource
   * */
  public function user(Request $request): UserResource
  {
    return UserResource::make($request->user());
  }
}
