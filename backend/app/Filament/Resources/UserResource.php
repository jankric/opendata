<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Models\Organization;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Section;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Personal')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->maxLength(20),
                        Forms\Components\TextInput::make('position')
                            ->label('Jabatan')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('avatar_url')
                            ->label('URL Avatar')
                            ->url()
                            ->maxLength(255),
                    ])->columns(2),

                Section::make('Informasi Organisasi')
                    ->schema([
                        Forms\Components\Select::make('organization_id')
                            ->label('Organisasi')
                            ->options(Organization::active()->pluck('name', 'id'))
                            ->searchable()
                            ->preload(),
                    ]),

                Section::make('Akses & Keamanan')
                    ->schema([
                        Forms\Components\Select::make('roles')
                            ->label('Role')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true),
                        Forms\Components\DateTimePicker::make('email_verified_at')
                            ->label('Email Terverifikasi')
                            ->displayFormat('d/m/Y H:i'),
                    ])->columns(2),

                Section::make('Password')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->label('Konfirmasi Password')
                            ->password()
                            ->same('password')
                            ->dehydrated(false)
                            ->required(fn (string $context): bool => $context === 'create'),
                    ])->columns(2)
                    ->visibleOn('create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar_url')
                    ->label('Avatar')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&color=7F9CF5&background=EBF4FF'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('organization.name')
                    ->label('Organisasi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('position')
                    ->label('Jabatan')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('roles.name')
                    ->label('Role')
                    ->separator(',')
                    ->colors([
                        'danger' => 'super-admin',
                        'warning' => 'organization-admin',
                        'success' => 'publisher',
                        'primary' => 'reviewer',
                        'secondary' => 'viewer',
                    ]),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('last_login_at')
                    ->label('Login Terakhir')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('organization')
                    ->label('Organisasi')
                    ->relationship('organization', 'name'),
                SelectFilter::make('roles')
                    ->label('Role')
                    ->relationship('roles', 'name'),
                TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
                TernaryFilter::make('email_verified_at')
                    ->label('Email Terverifikasi')
                    ->nullable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}