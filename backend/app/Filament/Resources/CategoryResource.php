<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Section;
use Filament\Tables\Filters\TernaryFilter;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Data Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Kategori')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Kategori')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('icon')
                            ->label('Icon')
                            ->placeholder('heroicon-o-tag')
                            ->maxLength(100),
                        Forms\Components\ColorPicker::make('color')
                            ->label('Warna'),
                    ])->columns(2),

                Section::make('Pengaturan')
                    ->schema([
                        Forms\Components\Select::make('parent_id')
                            ->label('Kategori Parent')
                            ->options(fn () => Category::whereNull('parent_id')->pluck('name', 'id'))
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true),
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
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50),
                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Parent')
                    ->searchable(),
                Tables\Columns\ColorColumn::make('color')
                    ->label('Warna'),
                Tables\Columns\TextColumn::make('datasets_count')
                    ->label('Jumlah Dataset')
                    ->counts('datasets')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Urutan')
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
            ->defaultSort('sort_order');
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'view' => Pages\ViewCategory::route('/{record}'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}