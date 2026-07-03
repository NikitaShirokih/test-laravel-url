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

    protected static ?string $navigationGroup = 'Ссылки';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', request()->user()?->getAuthIdentifier())
            ->withCount('visits');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('original_url')
                    ->label('Оригинальный URL')
                    ->placeholder('https://example.com/page')
                    ->helperText('Введите полный адрес страницы, начиная с http:// или https://')
                    ->required()
                    ->url()
                    ->maxLength(2048)
                    ->rules([new HttpUrlRule])
                    ->autofocus()
                    ->columnSpanFull(),
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
                    ->label('Короткая ссылка')
                    ->state(fn (ShortLink $record): string => $record->shortUrl())
                    ->copyable()
                    ->copyMessage('Короткая ссылка скопирована'),
                Tables\Columns\TextColumn::make('short_code')
                    ->label('Код')
                    ->copyable()
                    ->copyMessage('Код скопирован')
                    ->searchable(),
                Tables\Columns\TextColumn::make('visits_count')
                    ->label('Клики')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создана')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Открыть'),
                Tables\Actions\DeleteAction::make()
                    ->label('Удалить')
                    ->modalHeading('Удалить короткую ссылку?')
                    ->modalDescription('После удаления короткая ссылка перестанет работать.')
                    ->modalSubmitActionLabel('Удалить'),
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
                    ->label('Короткая ссылка')
                    ->state(fn (ShortLink $record): string => $record->shortUrl())
                    ->copyable(),
                Infolists\Components\TextEntry::make('short_code')
                    ->label('Код')
                    ->copyable(),
                Infolists\Components\TextEntry::make('visits_count')
                    ->label('Клики')
                    ->state(fn (ShortLink $record): int => (int) ($record->visits_count ?? $record->visits()->count())),
                Infolists\Components\TextEntry::make('created_at')
                    ->label('Создана')
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
