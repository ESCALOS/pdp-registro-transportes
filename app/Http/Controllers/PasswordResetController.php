<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordResetController extends Controller
{
    /**
     * Mostrar formulario de restablecimiento de contraseña
     */
    public function show(string $token)
    {
        // Buscar el token en la base de datos
        $hashedToken = hash('sha256', $token);
        
        $passwordReset = DB::table('password_reset_tokens')
            ->where('token', $hashedToken)
            ->first();

        if (!$passwordReset) {
            abort(404, 'El enlace de restablecimiento no es válido.');
        }

        // Verificar que el token no haya expirado (60 minutos)
        $expiresAt = \Carbon\Carbon::parse($passwordReset->created_at)->addMinutes(60);
        
        if (now()->greaterThan($expiresAt)) {
            // Eliminar token expirado
            DB::table('password_reset_tokens')
                ->where('token', $hashedToken)
                ->delete();
                
            abort(410, 'El enlace de restablecimiento ha expirado. Por favor, solicite uno nuevo.');
        }

        return view('password-reset', [
            'token' => $token,
            'email' => $passwordReset->email
        ]);
    }

    /**
     * Procesar el restablecimiento de contraseña
     */
    public function update(Request $request, string $token)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        // Buscar el token
        $hashedToken = hash('sha256', $token);
        
        $passwordReset = DB::table('password_reset_tokens')
            ->where('token', $hashedToken)
            ->first();

        if (!$passwordReset) {
            return back()->with('error', 'El enlace de restablecimiento no es válido.');
        }

        // Verificar expiración
        $expiresAt = \Carbon\Carbon::parse($passwordReset->created_at)->addMinutes(60);
        
        if (now()->greaterThan($expiresAt)) {
            DB::table('password_reset_tokens')
                ->where('token', $hashedToken)
                ->delete();
                
            return back()->with('error', 'El enlace de restablecimiento ha expirado. Por favor, solicite uno nuevo.');
        }

        // Actualizar contraseña del usuario
        $user = User::where('email', $passwordReset->email)->first();
        
        if (!$user) {
            return back()->with('error', 'Usuario no encontrado.');
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Eliminar el token usado
        DB::table('password_reset_tokens')
            ->where('token', $hashedToken)
            ->delete();

        return redirect()->route('password.reset.success');
    }

    /**
     * Mostrar página de éxito
     */
    public function success()
    {
        return view('password-reset-success');
    }
}
