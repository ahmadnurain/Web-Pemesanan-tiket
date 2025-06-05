<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Panel;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Management';


    public static function getRouteMiddleware(Panel $panel): array|string
    {
        // Panggil middleware bawaan
        $parentMiddleware = parent::getRouteMiddleware($panel);

        // Bisa return array gabungan
        if (is_array($parentMiddleware)) {
            return array_merge($parentMiddleware, [
                'super_admin',
            ]);
        }

        // Jika parent return string, jadikan array dulu
        return [$parentMiddleware, 'super_admin'];
    }



    public static function canViewAny(): bool
    {
        return Auth::user() && Auth::user()->role === 'super_admin';
    }

    public static function canView(Model $record): bool
    {
        return Auth::user() && Auth::user()->role === 'super_admin';
    }

    public static function canCreate(): bool
    {
        return Auth::user() && Auth::user()->role === 'super_admin';
    }

    public static function canEdit(Model $record): bool
    {
        return Auth::user() && Auth::user()->role === 'super_admin';
    }

    public static function canDelete(Model $record): bool
    {
        return Auth::user() && Auth::user()->role === 'super_admin';
    }
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user() && Auth::user()->role === 'super_admin';
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),

                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->dehydrateStateUsing(fn($state) => bcrypt($state)) // Encrypt password
                    ->hiddenOn('edit'),

                Forms\Components\Select::make('role')
                    ->options([
                        'user' => 'User',
                        'admin' => 'Admin',
                        'super_admin' => 'Super Admin',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable(),
                Tables\Columns\TextColumn::make('email')->sortable(),
                BadgeColumn::make('role')
                    ->getStateUsing(fn($record) => ucfirst($record->role)) // Menampilkan role yang telah didefinisikan
                    ->colors([
                        'admin' => 'primary', // Warna untuk 'admin'
                        'user' => 'secondary', // Warna untuk 'user'
                        'guest' => 'gray', // Warna untuk 'guest'
                    ]),
                // Tables\Columns\TextColumn::make('destination.name')
                //     ->label('Destination')
                //     ->sortable()
                //     ->getStateUsing(fn($record) => $record->destination->name ?? 'Super Admin'), // Menampilkan 'Super Admin' jika tidak ada data
                // Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'user' => 'User',
                        'admin' => 'Admin',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
