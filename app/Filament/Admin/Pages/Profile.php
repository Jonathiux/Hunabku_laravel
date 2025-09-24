<?php

namespace App\Filament\Admin\Pages;

use Auth;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Support\Facades\FilamentIcon;


class Profile extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected string $view = 'filament.admin.pages.profile';
    // protected static $navigationIcon = FilamentIcon::resolve(PanelsIconAlias::USER_MENU_PROFILE_ITEM);   
    protected static ?string $title = 'Mi Perfil';

    public $name;
    public $email;
    public $profile_photo;

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->profile_photo = $user->profile_photo_path ?? null;
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\FileUpload::make('profile_photo')
                ->label('Foto de perfil')
                ->image() 
                ->directory('profile-photos')
                ->disk('public') 
                ->nullable()
                ->avatar(),
            Forms\Components\TextInput::make('name')
                ->label('Nombre')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('email')
                ->label('Correo')
                ->email()
                ->required()
                ->maxLength(255),
        ];
    }

    public function save()
    {
        $user = Auth::user();

        $data = $this->form->getState();
        $user->name = $data['name'];
        $user->email = $data['email'];

        if (isset($data['profile_photo'])) {
            $user->profile_photo_path = $data['profile_photo'];
        }

        $user->save();

        Notification::make()
            ->success()
            ->title('Perfil actualizado correctamente.')
            ->send();
    }
}
