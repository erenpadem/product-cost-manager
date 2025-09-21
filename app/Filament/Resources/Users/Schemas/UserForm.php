<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('İsim') // Türkçeleştirildi
                    ->required(),
                TextInput::make('email')
                    ->label('E-posta Adresi') // Türkçeleştirildi
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at')
                    ->label('E-posta Doğrulama Tarihi'), // Türkçeleştirildi
                TextInput::make('password')
                    ->label('Şifre') // Türkçeleştirildi
                    ->password()
                    ->required(),
            ]);
    }
}
