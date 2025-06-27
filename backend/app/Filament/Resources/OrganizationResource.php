<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrganizationResource\Pages;
use App\Models\Organization;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Section;
use Filament\Tables\Filters\TernaryFilter;

class OrganizationResource extends Resource
{
    protected static ?string $model = Organization::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Organisasi')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Organisasi')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('type')
                            ->label('Tipe Organisasi')
                            ->placeholder('Dinas, Badan, Kantor, dll.')
                            ->maxLength(100),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true),
                    ])->columns(2),

                Section::make('Kontak & Alamat')
                    ->schema([
                        Forms\Components\TextInput::make('website')
                            ->label('Website')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label('Telepon')
                            ->tel()
                            ->maxLength(20),
                        Forms\Components\Textarea::make('address')
                            ->label('Alamat')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('logo_url')
                            ->label('URL Logo')
                            ->url()
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])->columns(3),

                Section::make('Metadata')
                    ->schema([
                        Forms\Components\KeyValue::make('metadata')
                            ->label('Metadata')
                            ->keyLabel('Key')
                            ->valueLabel('Value')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo_url')
                    ->label('Logo')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&color=7F9CF5&background=EBF4FF'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telepon'),
                Tables\Columns\TextColumn::make('users_count')
                    ->label('Jumlah User')
                    ->counts('users')
                    ->sortable(),
                Tables\Columns\TextColumn::make('datasets_count')
                    ->label('Jumlah Dataset')
                    ->counts('datasets')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
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
            ->defaultSort('name');
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
            'index' => Pages\ListOrganizations::route('/'),
            'create' => Pages\CreateOrganization::route('/create'),
            'view' => Pages\ViewOrganization::route('/{record}'),
            'edit' => Pages\EditOrganization::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}