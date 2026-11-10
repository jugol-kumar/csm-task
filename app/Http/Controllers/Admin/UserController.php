<?php

namespace App\Http\Controllers\Admin;

use App\Contract\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\OwnerRequest;
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

    public function store(OwnerRequest $request): JsonResponse
    {
        $owner = $this->userService->create($request->validated());
        return response()->json($owner);
    }

    public function show(User $owner)
    {
        $this->ensureOwner($owner);
        return response()->json($owner->load('store:id,owner_id,name,slug'));
    }

    public function update(OwnerRequest $request, User $owner)
    {
        return response()->json($this->userService->update($owner, $request->validated()));
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
