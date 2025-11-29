<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Requests\Profile\DeleteUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{

    public function getUser(Request $request)
    {
        return response()->json($request->user());
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $validated = $request->validated();
        $request->user()->update($validated);

        return response()->json($request->user());
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $validated = $request->validated();

        $request->user()->update([
            'password' => Hash::make($validated['password'])
        ]);

        return response()->json($request->user());
    }

    public function deleteUser(DeleteUserRequest $request)
    {
        $validated = $request->validated();

        $request->user()->delete($validated);

        return response()->json(['message' => 'User deleted successfully']);
    }
}
