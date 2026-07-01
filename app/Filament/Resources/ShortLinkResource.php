<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ShortLinkResource\Pages;
use App\Filament\Resources\ShortLinkResource\RelationManagers\LinkVisitsRelationManager;
use App\Models\ShortLink;
use App\Security\Rules\HttpUrlRule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

final class ShortLinkResource extends Resource
{
    protected static ?string $model = ShortLink::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?string $modelLabel = 'Короткая ссылка';

    protected static ?string $pluralModelLabel = 'Короткие ссылки';

    protected static ?string $navigationLabel = 'Короткие ссылки';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', auth()->id())
            ->withCount('visits');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('original_url')
                    ->label('Оригинальный URL')
                    ->placeholder('https://example.com/page')
                    ->required()
                    ->url()
                    ->maxLength(2048)
                    ->rules([new HttpUrlRule]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('original_url')
                    ->label('Оригинальный URL')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('short_url')
                    ->label('Короткий URL')
                    ->state(fn (ShortLink $record): string => $record->shortUrl()),
                Tables\Columns\TextColumn::make('short_code')
                    ->label('Короткий код')
                    ->searchable(),
                Tables\Columns\TextColumn::make('visits_count')
                    ->label('Клики')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата создания')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('original_url')
                    ->label('Оригинальный URL'),
                Infolists\Components\TextEntry::make('short_url')
                    ->label('Короткий URL')
                    ->state(fn (ShortLink $record): string => $record->shortUrl()),
                Infolists\Components\TextEntry::make('short_code')
                    ->label('Короткий код'),
                Infolists\Components\TextEntry::make('visits_total')
                    ->label('Всего переходов')
                    ->state(fn (ShortLink $record): int => $record->visits()->count()),
                Infolists\Components\TextEntry::make('created_at')
                    ->label('Дата создания')
                    ->dateTime('d.m.Y H:i'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            LinkVisitsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShortLinks::route('/'),
            'create' => Pages\CreateShortLink::route('/create'),
            'view' => Pages\ViewShortLink::route('/{record}'),
        ];
    }
}
