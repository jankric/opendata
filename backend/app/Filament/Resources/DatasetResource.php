<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DatasetResource\Pages;
use App\Models\Dataset;
use App\Models\Category;
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
use Filament\Forms\Components\TagsInput;

class DatasetResource extends Resource
{
    protected static ?string $model = Dataset::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Data Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Dasar')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul Dataset')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('Kategorisasi')
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->options(Category::active()->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('organization_id')
                            ->label('Organisasi')
                            ->options(Organization::active()->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->preload(),
                        TagsInput::make('tags')
                            ->label('Tags')
                            ->placeholder('Tambahkan tag...')
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Status & Publikasi')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'review' => 'Review',
                                'published' => 'Published',
                                'archived' => 'Archived',
                            ])
                            ->required()
                            ->default('draft'),
                        Forms\Components\Select::make('visibility')
                            ->label('Visibilitas')
                            ->options([
                                'public' => 'Public',
                                'private' => 'Private',
                            ])
                            ->required()
                            ->default('public'),
                        Forms\Components\Toggle::make('featured')
                            ->label('Featured')
                            ->default(false),
                        Forms\Components\TextInput::make('license')
                            ->label('Lisensi')
                            ->default('CC-BY-4.0')
                            ->maxLength(100),
                    ])->columns(2),

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
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('organization.name')
                    ->label('Organisasi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'secondary' => 'draft',
                        'warning' => 'review',
                        'success' => 'published',
                        'danger' => 'archived',
                    ]),
                Tables\Columns\IconColumn::make('featured')
                    ->label('Featured')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray'),
                Tables\Columns\TextColumn::make('downloads_count')
                    ->label('Downloads')
                    ->counts('downloads')
                    ->sortable(),
                Tables\Columns\TextColumn::make('views_count')
                    ->label('Views')
                    ->counts('views')
                    ->sortable(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Dibuat Oleh')
                    ->searchable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Dipublikasi')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'review' => 'Review',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ]),
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
                SelectFilter::make('organization')
                    ->label('Organisasi')
                    ->relationship('organization', 'name'),
                TernaryFilter::make('featured')
                    ->label('Featured'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('publish')
                    ->label('Publish')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(function (Dataset $record) {
                        $record->update([
                            'status' => 'published',
                            'published_at' => now(),
                        ]);
                    })
                    ->visible(fn (Dataset $record) => $record->status !== 'published'),
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
            'index' => Pages\ListDatasets::route('/'),
            'create' => Pages\CreateDataset::route('/create'),
            'view' => Pages\ViewDataset::route('/{record}'),
            'edit' => Pages\EditDataset::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes()
            ->with(['category', 'organization', 'creator']);
    }
}