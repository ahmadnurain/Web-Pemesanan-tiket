<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Destinations;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\DestinationsResource\Pages;

class DestinationsResource extends Resource
{
    protected static ?string $model = Destinations::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    public static function getEloquentQuery(): Builder
    {
        // Eager-load relasi supaya hemat query (hindari N+1)
        $query = parent::getEloquentQuery()->with(['category', 'admin', 'photos', 'videos']);

        if (Auth::user()?->role === 'super_admin') {
            return $query;
        }

        return $query->where('user_id', Auth::id());
    }

    public static function canCreate(): bool
    {
        return Auth::user() && Auth::user()->role === 'super_admin';
    }

    public static function canDelete(Model $record): bool
    {
        return Auth::user() && Auth::user()->role === 'super_admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Pemilik Destinasi')
                            ->relationship('admin', 'name')
                            ->visible(fn() => Auth::user()?->role === 'super_admin')
                            ->required(fn() => Auth::user()?->role === 'super_admin'),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Destinasi')
                                    ->required()
                                    ->regex('/^[a-zA-Z\s,.\-]+$/')
                                    ->maxLength(50)
                                    ->extraInputAttributes([
                                        'oninput' => "this.value = this.value.replace(/[^a-zA-Z\\s,\\.\\-]/g, '').slice(0, 50);"
                                    ]),

                                Forms\Components\Select::make('category_id')
                                    ->label('Kategori')
                                    ->relationship('category', 'name')
                                    ->required(),

                                Forms\Components\TextInput::make('ticket_price')
                                    ->label('Harga Tiket')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxLength(15)
                                    ->extraInputAttributes(['oninput' => "if(this.value.length > 15) this.value = this.value.slice(0, 15);"]),

                                Forms\Components\TextInput::make('location')
                                    ->label('Lokasi')
                                    ->required()
                                    ->regex('/^[a-zA-Z\s,.\-]+$/')
                                    ->maxLength(80)
                                    ->extraInputAttributes([
                                        'oninput' => "this.value = this.value.replace(/[^a-zA-Z\\s,\\.\\-]/g, '').slice(0, 80);"
                                    ]),

                                Forms\Components\TextInput::make('operating_hours')
                                    ->label('Jam Operasional')
                                    ->placeholder('Contoh: Senin - Minggu, 08:00 - 17:00')
                                    ->maxLength(100),

                                Forms\Components\Textarea::make('description')
                                    ->label('Deskripsi')
                                    ->required()
                                    ->maxLength(5000)
                                    ->extraInputAttributes(['oninput' => "if(this.value.length > 5000) this.value = this.value.slice(0, 5000);"])
                                    ->columnSpanFull(),

                                Forms\Components\Repeater::make('photos')
                                    ->relationship('photos')
                                    ->schema([
                                        Forms\Components\FileUpload::make('path')
                                            ->label('Foto')
                                            ->image()
                                            ->directory('uploads/photos')
                                            ->reorderable()
                                            ->appendFiles()
                                            ->previewable(true)
                                            ->required(),
                                    ])
                                    ->columnSpanFull(),

                                Forms\Components\Repeater::make('videos')
                                    ->relationship('videos')
                                    ->defaultItems(0)
                                    ->minItems(0)
                                    ->deletable(true)
                                    ->reorderable()
                                    ->schema([
                                        Forms\Components\FileUpload::make('path')
                                            ->label('Video')
                                            ->disk('public')
                                            ->directory('uploads/videos')
                                            ->acceptedFileTypes(['video/mp4', 'video/mpeg', 'video/avi'])
                                            ->maxSize(10240) // 10 MB
                                            ->required(),
                                    ])
                                    ->columnSpanFull(),

                                Forms\Components\Section::make('Variasi Tiket (Mix)')
                                    ->description('Tambahkan jenis tiket (misal: Dewasa, Anak) jika ingin opsi checkout campuran.')
                                    ->schema([
                                        Forms\Components\Repeater::make('ticketTypes')
                                            ->relationship('ticketTypes')
                                            ->schema([
                                                Forms\Components\TextInput::make('name')
                                                    ->label('Nama Tiket (e.g. Dewasa)')
                                                    ->required(),
                                                Forms\Components\TextInput::make('price')
                                                    ->label('Harga')
                                                    ->required()
                                                    ->numeric()
                                                    ->prefix('Rp')
                                                    ->minValue(0),
                                                Forms\Components\Textarea::make('description')
                                                    ->label('Keterangan')
                                                    ->rows(2)
                                                    ->columnSpanFull(),
                                            ])
                                            ->columns(2)
                                            ->defaultItems(0)
                                            ->reorderable()
                                            ->createItemButtonLabel('Tambah Jenis Tiket'),
                                    ])
                                    ->collapsed(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Kolom Pemilik/Admin (relasi admin->name)
                Tables\Columns\TextColumn::make('admin.name')
                    ->label('Pemilik / Admin')
                    ->searchable()
                    ->sortable()
                    ->visible(fn() => Auth::user()?->role === 'super_admin'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Destinasi')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable(),

                Tables\Columns\TextColumn::make('location')
                    ->label('Lokasi')
                    ->searchable(),

                Tables\Columns\TextColumn::make('operating_hours')
                    ->label('Jam Operasional')
                    ->searchable(),

                Tables\Columns\TextColumn::make('photos')
                    ->label('Gambar Destinasi')
                    ->formatStateUsing(function ($state, $record) {
                        $photos = $record->photos;

                        if ($photos->isEmpty()) {
                            return 'Tidak ada foto, upload foto';
                        }

                        $output = '<div style="display:flex;flex-wrap:wrap;gap:8px">';
                        foreach ($photos as $photo) {
                            $src = asset('storage/' . $photo->path);
                            $output .= '<img src="' . e($src) . '" width="100" height="100" style="object-fit:cover;border-radius:8px" />';
                        }
                        $output .= '</div>';

                        return $output;
                    })
                    ->html()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('videos')
                    ->label('Video Destinasi')
                    ->formatStateUsing(function ($state, $record) {
                        $videos = $record->videos;

                        if ($videos->isEmpty()) {
                            return 'Tidak ada video, upload video';
                        }

                        $output = '<div style="display:flex;flex-wrap:wrap;gap:8px">';
                        foreach ($videos as $video) {
                            $src = asset('storage/' . $video->path);
                            $output .= '
                                <video width="140" height="100" controls style="border-radius:8px;object-fit:cover">
                                    <source src="' . e($src) . '" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            ';
                        }
                        $output .= '</div>';

                        return $output;
                    })
                    ->html()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Pemilik / Admin')
                    ->relationship('admin', 'name')
                    ->visible(fn() => Auth::user()?->role === 'super_admin'),

                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
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
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDestinations::route('/'),
            'create' => Pages\CreateDestinations::route('/create'),
            'edit' => Pages\EditDestinations::route('/{record}/edit'),
        ];
    }

    // Helper untuk format rupiah 
    public static function formatRupiah($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}
