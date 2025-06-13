<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('messages.user_details'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('messages.name'))
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label(__('messages.email'))
                            ->email()
                            ->required()
                            ->unique(User::class, 'email', ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('password')
                            ->label(__('messages.password'))
                            ->password()
                            ->required(fn (string $context): bool => $context === 'create')
                            ->minLength(8)
                            ->dehydrated(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->helperText(__('messages.password_helper')),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('messages.roles_and_permissions'))
                    ->schema([
                        Forms\Components\CheckboxList::make('roles')
                            ->label(__('messages.roles'))
                            ->relationship('roles', 'name')
                            ->options(function () {
                                $user = auth()->user();

                                if ($user->hasRole('super_admin')) {
                                    return Role::all()->pluck('name', 'id');
                                }

                                return [];
                            })
                            ->visible(fn () => auth()->user()->hasRole('super_admin'))
                            ->columnSpanFull()
                            ->helperText(__('messages.roles_and_permissions_helper')),

                        Forms\Components\Placeholder::make('current_roles')
                            ->label(__('messages.current_roles_helper'))
                            ->content(function (?Model $record = null): string {
                                if (! $record) {
                                    return __('no');
                                }

                                $roles = $record->getRoleNames()->toArray();

                                return empty($roles) ? __('no_roles') : implode(', ', $roles);
                            })
                            ->visible(fn () => ! auth()->user()->hasRole('super_admin')),
                    ]),

                Forms\Components\Section::make(__('messages.statistics'))
                    ->schema([
                        Forms\Components\Placeholder::make('restaurants_count')
                            ->label(__('messages.restaurants_count'))
                            ->content(function (?Model $record = null): string {
                                if (! $record) {
                                    return '0';
                                }

                                return (string) $record->restaurants()->count();
                            }),

                        Forms\Components\Placeholder::make('created_at')
                            ->label(__('messages.created_at'))
                            ->content(function (?Model $record = null): string {
                                if (! $record) {
                                    return '-';
                                }

                                return $record->created_at->format('d.m.Y H:i');
                            }),

                        Forms\Components\Placeholder::make('email_verified_at')
                            ->label(__('messages.email_verified_at'))
                            ->content(function (?Model $record = null): string {
                                if (! $record) {
                                    return '-';
                                }

                                return $record->email_verified_at ?
                                    $record->email_verified_at->format('d.m.Y H:i') :
                                    __('not_verified');
                            }),
                    ])
                    ->columns(3)
                    ->visibleOn('edit'),
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

                Tables\Columns\TextColumn::make('email')
                    ->label(__('messages.email'))
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('roles.name')
                    ->label(__('messages.roles'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'super_admin' => 'danger',
                        'restaurant_admin' => 'warning',
                        default => 'gray',
                    })
                    ->separator(', '),

                Tables\Columns\TextColumn::make('restaurants_count')
                    ->label(__('messages.restaurants_count'))
                    ->counts('restaurants')
                    ->sortable()
                    ->badge()
                    ->color('success'),

                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label(__('messages.email_verified_at'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('messages.created_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('messages.updated_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('roles')
                    ->label(__('messages.roles'))
                    ->relationship('roles', 'name')
                    ->preload()
                    ->multiple(),

                Tables\Filters\TernaryFilter::make('email_verified_at')
                    ->label(__('messages.email_verified_at'))
                    ->boolean()
                    ->trueLabel(__('messages.verified'))
                    ->falseLabel(__('messages.not_verified'))
                    ->native(false),

                Tables\Filters\Filter::make('has_restaurants')
                    ->label(__('messages.has_restaurants'))
                    ->query(fn (Builder $query): Builder => $query->has('restaurants'))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (Model $record) => auth()->user()->hasRole('super_admin') &&
                        $record->id !== auth()->id() // Do not allow deleting the current user
                    ),

                Tables\Actions\Action::make('reset_password')
                    ->label(__('messages.reset_password'))
                    ->icon('heroicon-o-key')
                    ->color('warning')
                    ->visible(fn (Model $record) => auth()->user()->hasRole('super_admin') &&
                        $record->id !== auth()->id()
                    )
                    ->form([
                        Forms\Components\TextInput::make('new_password')
                            ->label(__('messages.new_password'))
                            ->password()
                            ->required()
                            ->minLength(8)
                            ->confirmed(),

                        Forms\Components\TextInput::make('new_password_confirmation')
                            ->label(__('messages.confirm_new_password'))
                            ->password()
                            ->required(),
                    ])
                    ->action(function (Model $record, array $data): void {
                        $record->update([
                            'password' => Hash::make($data['new_password']),
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->title(__('messages.password_reset_success'))
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()->hasRole('super_admin')),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        if ($user->hasRole('super_admin')) {
            return parent::getEloquentQuery();
        }

        return parent::getEloquentQuery()->where('id', $user->id);
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasRole('super_admin');
    }

    public static function canEdit(Model $record): bool
    {
        $user = auth()->user();

        return $user->hasRole('super_admin') || $record->id === $user->id;
    }

    public static function canDelete(Model $record): bool
    {
        $user = auth()->user();

        return $user->hasRole('super_admin') && $record->id !== $user->id;
    }

    public static function canView(Model $record): bool
    {
        $user = auth()->user();

        return $user->hasRole('super_admin') || $record->id === $user->id;
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasAnyRole(['super_admin', 'restaurant_admin']);
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
        if (auth()->user()->hasRole('super_admin')) {
            return static::getModel()::count();
        }

        return null;
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.navigation.users');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.groups.management');
    }
}
