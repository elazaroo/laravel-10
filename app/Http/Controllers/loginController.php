<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class loginController extends Controller
{
    public function apiRememberToken($token)
    {
        $user = User::where('remember_token', $token)->first();
        if ($user) {
            $data[] = [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email
            ];
            Log::info('[loginController] apiRememberToken - Se han obtenido los datos del usuario ', array($data), ' a través del token.');
            return response()->json($data, 200);
        } else {
            $data[] = [
                'message' => 'Token no válido.',
                'status' => 401
            ];
            Log::info('[loginController] apiRememberToken - Token no válido.');
            return response()->json($data, 401);
        }
    }

    public function register(Request $request)
    {
        $user = new User();
        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        // Verificar si el email ya existe en la base de datos
        $existingEmail = User::where('email', $request->email)->first();
        $existingUsername = User::where('username', $request->username)->first();
        if ($existingEmail) {
            session()->flash('error', 'Email ya en uso.');
            return back()->withErrors(['email' => 'El email ya existe.'])->withInput(['username', 'name', 'email']);
        } else {
            if ($existingUsername) {
                session()->flash('error', 'Nombre de usuario ya en uso.');
                return back()->withErrors(['username' => 'El nombre de usuario ya existe.'])->withInput(['username', 'name', 'email']);
            } else {
                $user->save();
                $token = Uuid::uuid4()->toString();
                $user->token()->create([
                    'token' => $token,
                    //expira en 10 día
                    'expire' => now()->addDays(10)
                ]);
                Auth::login($user);
                return redirect('/');
            }
        }
    }

    public function login(Request $request)
    {
        $credentials = [
            'username' => $request->username,
            'password' => $request->password
        ];

        $remember = ($request->has('remember')) ? true : false;

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            Log::info('[loginController] login - Login exitoso { ' . $credentials['username'] . ' }');
            $userId = Auth::user();
            $user = User::find($userId->id);
            $token = Uuid::uuid4()->toString();
            $user->token()->create([
                'token' => $token,
                //expira en 10 día
                'expire' => now()->addDays(10)
            ]);
            return redirect()->intended(('/'));
        } else {
            Log::info('[loginController] login - Login fallido { ' . $credentials['username'] . ' }');
            session()->flash('error', 'Usuario o contraseña incorrectos.');
            return redirect()->back()->withErrors(['login' => 'Usuario o contraseña incorrectos.'])->withInput(['username']);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
