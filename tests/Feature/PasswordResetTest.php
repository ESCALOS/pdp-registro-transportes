<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que la página de solicitud de restablecimiento se carga correctamente
     */
    public function test_forgot_password_page_loads(): void
    {
        $response = $this->get(route('password.request'));
        
        $response->assertStatus(200);
        $response->assertSee('¿Olvidó su contraseña?');
    }

    /**
     * Test que se envía el correo de restablecimiento
     */
    public function test_password_reset_email_is_sent(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $response = $this->post(route('password.request'), [
            'email' => 'test@example.com',
        ]);

        // Verificar que se guardó el token
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'test@example.com',
        ]);

        // Verificar que se intentó enviar el correo
        Mail::assertSent(PasswordResetMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    /**
     * Test que el formulario de restablecimiento se muestra con token válido
     */
    public function test_password_reset_form_shows_with_valid_token(): void
    {
        $user = User::factory()->create();
        
        $token = 'test-token-123';
        $hashedToken = hash('sha256', $token);

        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => $hashedToken,
            'created_at' => now(),
        ]);

        $response = $this->get(route('password.reset.show', $token));
        
        $response->assertStatus(200);
        $response->assertSee('Restablecer Contraseña');
        $response->assertSee($user->email);
    }

    /**
     * Test que el token expirado retorna error
     */
    public function test_expired_token_returns_error(): void
    {
        $user = User::factory()->create();
        
        $token = 'test-token-123';
        $hashedToken = hash('sha256', $token);

        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => $hashedToken,
            'created_at' => now()->subMinutes(61), // Expirado
        ]);

        $response = $this->get(route('password.reset.show', $token));
        
        $response->assertStatus(410); // Gone
    }

    /**
     * Test que la contraseña se actualiza correctamente
     */
    public function test_password_is_updated_successfully(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('old-password'),
        ]);
        
        $token = 'test-token-123';
        $hashedToken = hash('sha256', $token);

        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => $hashedToken,
            'created_at' => now(),
        ]);

        $response = $this->put(route('password.reset.update', $token), [
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);

        // Verificar que se redirige a la página de éxito
        $response->assertRedirect(route('password.reset.success'));

        // Verificar que la contraseña cambió
        $user->refresh();
        $this->assertTrue(Hash::check('new-password-123', $user->password));

        // Verificar que el token fue eliminado
        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => $user->email,
        ]);
    }

    /**
     * Test que las contraseñas deben coincidir
     */
    public function test_passwords_must_match(): void
    {
        $user = User::factory()->create();
        
        $token = 'test-token-123';
        $hashedToken = hash('sha256', $token);

        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => $hashedToken,
            'created_at' => now(),
        ]);

        $response = $this->put(route('password.reset.update', $token), [
            'password' => 'new-password-123',
            'password_confirmation' => 'different-password',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /**
     * Test que la contraseña debe tener mínimo 8 caracteres
     */
    public function test_password_must_be_at_least_8_characters(): void
    {
        $user = User::factory()->create();
        
        $token = 'test-token-123';
        $hashedToken = hash('sha256', $token);

        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => $hashedToken,
            'created_at' => now(),
        ]);

        $response = $this->put(route('password.reset.update', $token), [
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /**
     * Test que la página de éxito se carga
     */
    public function test_success_page_loads(): void
    {
        $response = $this->get(route('password.reset.success'));
        
        $response->assertStatus(200);
        $response->assertSee('¡Contraseña Restablecida!');
    }
}
