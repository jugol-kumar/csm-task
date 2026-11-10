<?php

namespace App\Http\Controllers\Admin;

use App\Contract\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
    }

    public function index(Request $request)
    {
        return response()->json($this->userService->paginate((string) $request->query('search', '')));
    }

    public function store(UserRequest $request): JsonResponse
    {
        $user = $this->userService->create($request->validated());
        return response()->json($user);
    }

    public function show(User $user)
    {
        $this->ensureOwner($user);
        return response()->json($user->load('store:id,owner_id,name,slug'));
    }

    public function update(UserRequest $request, User $user)
    {
        return response()->json($this->userService->update($user, $request->validated()));
    }

    public function destroy(User $user)
    {
        $this->userService->delete($user);
        return response()->noContent();
    }

    private function ensureOwner(User $user): void
    {
        abort_unless($user->role === Role::$OWNER, 404);
    }
}
