<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user()->loadMissing(['roles.permissions', 'studentProfile', 'lecturerProfile']);

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $user->roles->pluck('name')->values(),
            'permissions' => $user->roles->flatMap(fn ($role) => $role->permissions->pluck('name'))->unique()->values(),
        ]);
    }
}
