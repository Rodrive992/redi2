<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validar campos
        $credentials = $request->validate([
            'cuil' => 'required|string', // Cambiado de email a cuil
            'password' => 'required|string',
        ]);

        // Intentar autenticación usando cuil
        if (Auth::attempt([
            'cuil' => $credentials['cuil'],
            'password' => $credentials['password']
        ], $request->remember)) {
            
            $request->session()->regenerate();
            
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Redirección según dependencia
            if ($user->dependencia === 'dgp') {
                return redirect()->intended(route('redidgp'));
            }
            
            return redirect()->intended(route('redi.externo'));
        }

        // Si falla, mostrar mensaje de error
        throw ValidationException::withMessages([
            'cuil' => __('auth.failed'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}