<?php
namespace App\Filament\Pages;

use App\Models\LoginCode;
use App\Models\User;
use Auth;
use Filament\Actions\Action;
use Filament\Auth\Pages\Login;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Mail;
use Filament\Notifications\Notification;

class CustomLogin extends Login
{

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getEmailFormComponent()
                    ->label('Correo electrónico'),
                TextInput::make('code')
                    ->label('Código de verificación')
                    ->numeric()
                    ->length(6)
                    ->placeholder('Ingrese el código de 6 dígitos')
                    // ->required(),
            ]);
    }

    public function getTitle(): string
    {
        return 'Iniciar sesión'; // Cambia "Sign in"
    }
    
    protected function getFormActions(): array
    {
        return [
            // Botón para enviar código
            Action::make('sendCode')
                ->label('Enviar código')
                ->button() 
                ->action(fn () => $this->sendCode())
                ->color('primary'),

            // Botón para iniciar sesión con el código
            Action::make('login')
                ->label('Iniciar sesión')
                ->submit('authenticate')
                ->color('success'),
                
        ];
    }

    public function sendCode(): void
    {
        $email = $this->form->getState()['email'] ?? null;

        if (!$email) {
            Notification::make()
                ->title('Ingresa tu correo primero.')
                ->danger()
                ->send();
            return;
        }

        $user = User::firstOrCreate(
            ['email' => $email],
            ['name' => explode('@', $email)[0]] // nombre predeterminado
        );

        $code = rand(100000, 999999);

        LoginCode::create([
            'email' => $email,
            'code' => $code,
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::raw("Tu código de acceso es: $code", function ($message) use ($email) {
            $message->to($email)->subject('Código de acceso');
        });

        Notification::make()
            ->title('Se envió un código de acceso a tu correo.')
            ->success()
            ->send();
    }

    public function authenticate(): ?\Filament\Auth\Http\Responses\Contracts\LoginResponse
    {
        $data = $this->form->getState();

        $record = LoginCode::where('email', $data['email'])
            ->where('code', $data['code'])
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$record) {
            Notification::make()
                ->title('El código es inválido o ha expirado.')
                ->danger()
                ->send();
            return null;
        }

        // Asegurarse de que el usuario exista (ya fue creado en sendCode)
        $user = User::firstOrCreate(
            ['email' => $data['email']],
            ['name' => explode('@', $data['email'])[0]]
        );

        Auth::login($user, $data['remember'] ?? false);
        session()->regenerate();

        return app(\Filament\Auth\Http\Responses\Contracts\LoginResponse::class);
    }



}
