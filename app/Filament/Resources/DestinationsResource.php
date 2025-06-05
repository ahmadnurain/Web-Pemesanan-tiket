<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Panel;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Destinations;
use Tables\Columns\HtmlColumn;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DestinationsResource\Pages;
use App\Filament\Resources\DestinationsResource\RelationManagers;

class DestinationsResource extends Resource
{
    protected static ?string $model = Destinations::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';



    // Tambahkan method ini di sini:
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

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
        // Debug data request saat formulir diakses

        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Pemilik Destinasi')
                            ->relationship('admin', 'name') // pastikan relasi "user()" di model Destinations
                            ->visible(fn() => Auth::user()?->role === 'admin') // hanya terlihat oleh Super Admin
                            ->required(fn() => Auth::user()?->role === 'admin'),
                        // Grid untuk membuat dua kolom sejajar
                        Forms\Components\Grid::make(2) // Membuat grid dengan 2 kolom
                            ->schema([
                                // Nama Destinasi (Kiri)
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Destinasi')
                                    ->required()
                                    ->maxLength(255),

                                // Kategori (Kanan)
                                Forms\Components\Select::make('category_id')
                                    ->label('Kategori')
                                    ->relationship('category', 'name') // Relasi ke tabel kategori
                                    ->required(),

                                Forms\Components\TextInput::make('ticket_price')
                                    ->label('Harga Tiket')
                                    ->required()
                                    ->numeric()
                                    ->maxLength(500),


                                // Lokasi
                                Forms\Components\TextInput::make('location')
                                    ->label('Lokasi')
                                    ->required()
                                    ->maxLength(255),


                                // Deskripsi
                                Forms\Components\Textarea::make('description')
                                    ->label('Deskripsi')
                                    ->required()
                                    ->maxLength(500)->columnSpanFull(), // Membentang penuh dalam form
                                // Foto (Multiple Upload)
                                Forms\Components\Repeater::make('photos') // Relasi ke tabel photos
                                    ->relationship('photos') // Menghubungkan dengan relasi photos
                                    ->schema([
                                        Forms\Components\FileUpload::make('path') // Path untuk foto
                                            ->label('Foto')
                                            ->image()
                                            ->directory('uploads/photos') // Direktori penyimpanan
                                            ->reorderable()
                                            ->appendFiles()
                                            ->previewable(true)
                                            ->required(),
                                    ])
                                    ->columnSpanFull(), // Membentang penuh dalam form


                                // Video (Optional)
                                Forms\Components\Repeater::make('videos') // Relasi ke tabel videos
                                    ->relationship('videos') // Menghubungkan dengan relasi videos
                                    ->schema([
                                        Forms\Components\FileUpload::make('path') // Path untuk video
                                            ->label('Video')
                                            ->acceptedFileTypes(['video/mp4', 'video/mpeg', 'video/avi'])
                                            ->maxSize(10240) // Maksimal 10MB
                                            ->directory('uploads/videos')
                                            ->nullable(),
                                    ])
                                    ->columnSpanFull(), // Membentang penuh dalam form
                            ]),







                    ]),
            ]);
    }



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name') // Nama Destinasi
                    ->label('Nama Destinasi')
                    ->searchable() // Bisa dicari
                    ->sortable(), // Bisa diurutkan

                Tables\Columns\TextColumn::make('category.name') // Nama Kategori dari Relasi
                    ->label('Kategori')
                    ->sortable(), // Bisa diurutkan

                Tables\Columns\TextColumn::make('location') // Lokasi
                    ->label('Lokasi')
                    ->searchable(),






                Tables\Columns\TextColumn::make('photos') // Mengambil relasi photos
                    ->label('Gambar Destinasi')
                    ->formatStateUsing(function ($state, $record) {
                        // Ambil relasi photos untuk destinasi ini
                        $photos = $record->photos; // Mendapatkan koleksi foto yang terhubung dengan destinasi ini

                        // Jika tidak ada foto, tampilkan pesan default
                        if ($photos->isEmpty()) {
                            return 'Tidak ada foto, upload foto'; // Pesan jika tidak ada foto
                        }

                        // Render semua gambar yang terupload berdasarkan destinasi_id
                        $output = '<div style="display: flex; ">';
                        foreach ($photos as $photo) {
                            // Tampilkan gambar untuk setiap foto yang terupload
                            $output .= '<div style="margin: 5px;">
                                                <img src="' . asset('storage/' . $photo->path) . '" width="100" height="100">
                                            </div>';
                        }
                        $output .= '</div>';
                        return $output; // Mengembalikan HTML yang menampilkan gambar
                    })
                    ->html() // Render HTML
                    ->sortable(), // Menambahkan kemampuan sortir

                Tables\Columns\TextColumn::make('updated_at') // Tanggal Diubah
                    ->label('Diperbarui Pada')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                // Menambahkan kolom untuk video
                Tables\Columns\TextColumn::make('videos') // Mengambil relasi videos
                    ->label('Video Destinasi')
                    ->formatStateUsing(function ($state, $record) {
                        // Ambil relasi videos untuk destinasi ini
                        $videos = $record->videos; // Mendapatkan koleksi video yang terhubung dengan destinasi ini

                        // Jika tidak ada video, tampilkan pesan default
                        if ($videos->isEmpty()) {
                            return 'Tidak ada video, upload video'; // Pesan jika tidak ada video
                        }

                        // Render semua video yang terupload berdasarkan destinasi_id
                        $output = '<div style="display: flex; ">';
                        foreach ($videos as $video) {
                            // Tampilkan video untuk setiap file video yang terupload
                            $output .= '<div style="margin: 5px;">
                                            <video width="100" height="100" controls>
                                                <source src="' . asset('storage/' . $video->path) . '" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>';
                        }
                        $output .= '</div>';
                        return $output; // Mengembalikan HTML yang menampilkan video
                    })
                    ->html() // Render HTML
                    ->sortable(), // Menambahkan kemampuan sortir


            ])
            ->filters([
                // Filter Berdasarkan Kategori
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(), // Menghapus tombol di footer // Aksi Edit
                Tables\Actions\EditAction::make(), // Aksi Edit
                Tables\Actions\DeleteAction::make(), // Aksi Hapus
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
            'index' => Pages\ListDestinations::route('/'),
            'create' => Pages\CreateDestinations::route('/create'),
            'edit' => Pages\EditDestinations::route('/{record}/edit'),
        ];
    }
}
