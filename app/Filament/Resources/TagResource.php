<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TagResource\Pages;
use App\Models\Tag;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('messages.tag_name'))
                    ->required()
                    ->maxLength(255)
                    ->unique(Tag::class, 'name', ignoreRecord: true),

                Forms\Components\Textarea::make('description')
                    ->label(__('messages.description'))
                    ->rows(3)
                    ->maxLength(500),

                Forms\Components\ColorPicker::make('color')
                    ->label(__('messages.color'))
                    ->hex(),

                Forms\Components\Toggle::make('is_active')
                    ->label(__('messages.active'))
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('messages.name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label(__('messages.description'))
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();

                        return strlen($state) > 50 ? $state : null;
                    }),

                Tables\Columns\ColorColumn::make('color')
                    ->label(__('messages.color')),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('messages.status'))
                    ->boolean(),

                Tables\Columns\TextColumn::make('restaurants_count')
                    ->label(__('messages.restaurants_count'))
                    ->counts('restaurants')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('messages.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('messages.status'))
                    ->boolean()
                    ->trueLabel(__('messages.only_active'))
                    ->falseLabel(__('messages.only_inactive'))
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                $user = auth()->user();
                // Only for super_admin
                if (! $user->hasRole('super_admin')) {
                    // Show only tags related to the user's restaurants
                    $restaurantIds = $user->restaurants()->pluck('id');
                    $query->whereHas('restaurants', function (Builder $subQuery) use ($restaurantIds) {
                        $subQuery->whereIn('restaurants.id', $restaurantIds);
                    });
                }

                return $query;
            });
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
            'index' => Pages\ListTags::route('/'),
            'create' => Pages\CreateTag::route('/create'),
            'edit' => Pages\EditTag::route('/{record}/edit'),
        ];
    }

    // Limit access to the resource
    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user && ($user->hasRole('super_admin') || $user->hasRole('restaurant_admin'));
    }

    // Tags can only be created by super_admin
    public static function canCreate(): bool
    {
        return auth()->user()?->hasRole('super_admin') ?? false;
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.navigation.tags');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.groups.management');
    }
}
