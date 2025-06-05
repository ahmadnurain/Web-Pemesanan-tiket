<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Panel;
use Filament\Tables;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CategoryResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CategoryResource\RelationManagers;


class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
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
                Forms\Components\Card::make() // Membuat Card
                    // ->columnSpan(1) // Mengatur span kolom menjadi 2, lebih kecil dari full
                    ->schema([ // Field di dalam Card
                        Forms\Components\TextInput::make('name') // Field Nama
                            ->label('Nama')
                            ->required()
                            ->maxLength(255)

                    ]),
            ]);
    }



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
