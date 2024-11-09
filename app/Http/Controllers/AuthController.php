<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\CreateUserRequest;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function register(CreateUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'email' => $validated['email'],
            'name' => $validated['name'],
            'password' => Hash::make($validated['password']),
        ]);

        event(new Registered($user));

        $token = $user->createToken($validated['hostname'], ['*'], now()->addWeek())->plainTextToken;

        return response()->json([
            'user' => $user,
            'access_token' => $token,
        ], 201);
    }

    public function login(LoginUserRequest $request)
    {
        $validated = $request->validated();

        if (!Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']], true)) {
            throw ValidationException::withMessages([
                'email' => ['Les informations de connexion sont incorrectes.'],
            ]);
        }

        $user = User::where('email', $validated['email'])->firstOrFail();

        $token = $user->createToken($validated['hostname'], ['*'], now()->addWeek())->plainTextToken;

        return response()->json([
            'user' => $user,
            'access_token' => $token,
        ]);
    }

    public function logout (Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['success' => true]);
    }

    public function sendResetLinkEmail(Request $request)
{
    $request->validate(['email' => 'required|email']);

    // Vérification de l'email dans la base de données
    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['message' => 'This email address is not registered.'], 404);
    }

    // Envoi du lien de réinitialisation si l'email existe
    $status = Password::sendResetLink($request->only('email'));

    return $status === Password::RESET_LINK_SENT
        ? response()->json(['message' => __($status)], 200)
        : response()->json(['message' => __($status)], 400);
}


    public function resetPassword(Request $request)
    {
        // Valider les informations pour la réinitialisation
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation errors', 'errors' => $validator->errors()], 422);
        }

        // Tenter de réinitialiser le mot de passe
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => __($status)], 200);
        } else {
            return response()->json(['message' => __($status)], 400);
        }
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
