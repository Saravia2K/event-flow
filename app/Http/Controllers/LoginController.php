<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Muestra el formulario de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Maneja el intento de login
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            return $this->authenticated($request, $user);
        }

        throw ValidationException::withMessages([
            'email' => "Correo y contraseña incorrectos",
        ]);
    }

    /**
     * Maneja la redirección post-autenticación
     * 
     * @param User $user
     */
    protected function authenticated(Request $request, User $user)
    {
        $user = Auth::user();
        return redirect()->intended(
            route(
                $user->isAdmin()
                ? 'organizer.dashboard'
                : 'index'
            )
        );
    }

    /**
     * Cierra la sesión
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route("login-form"));
    }
}