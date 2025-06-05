<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\TicketTransaction;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TicketTransactionResource\Pages;
use App\Filament\Resources\TicketTransactionResource\RelationManagers;

class TicketTransactionResource extends Resource
{
    protected static ?string $model = TicketTransaction::class; // Model yang digunakan

    protected static ?string $navigationIcon = 'heroicon-o-ticket'; // Ikon sidebar
    protected static ?string $navigationLabel = 'Ticket Transactions'; // Label sidebar
    protected static ?string $navigationGroup = 'Management'; // Group di sidebar



    public static function canCreate(): bool
    {
        return Auth::user() && Auth::user()->role === 'super_admin';
    }
    public static function canDelete(Model $record): bool
    {
        return Auth::user() && Auth::user()->role === 'super_admin';
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (Auth::user()?->role !== 'super_admin') {
            $query->whereHas('destination', function ($query) {
                $query->where('user_id', Auth::id());
            });
        }

        return $query;
    }

    // Form untuk Create/Edit
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Grid::make(2) // Set to 2 columns
                            ->schema([
                                Forms\Components\Select::make('destination_id')
                                    ->relationship('destination', 'name')
                                    ->columnSpan(1) // Ensure it takes 1 column space
                                    ->required(),

                                Forms\Components\TextInput::make('name')
                                    ->label('Name')
                                    ->required(),

                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->label('Email')
                                    ->required(),

                                Forms\Components\TextInput::make('phone_number')
                                    ->label('Phone Number')
                                    ->required()
                                    ->numeric() // Ensures only numeric input is allowed
                                    ->maxLength(15) // Optional: limit the length of the phone number (for example, 15 characters max)
                                    ->minLength(10) // Optional: minimum length for the phone number (for example, 10 characters)
                                    ->default('')
                                    ->helperText('Please enter a valid phone number (numbers only).'),


                                Forms\Components\TextInput::make('ticket_code')
                                    ->label('Ticket Code')
                                    ->required(),

                                Forms\Components\Select::make('ticket_status')
                                    ->label('Ticket Status')
                                    ->options([
                                        'unused' => 'Unused',
                                        'used' => 'Used',
                                    ])
                                    ->required(),

                                Forms\Components\TextInput::make('amount')
                                    ->label('Amount')
                                    ->numeric()
                                    ->prefix('IDR ')
                                    ->required()
                                    ->reactive() // Make it reactive if you need to update dynamically
                                    ->afterStateUpdated(function ($state) {
                                        return number_format($state, 0, ',', '.'); // Format the value as IDR
                                    }),


                                Forms\Components\Select::make('payment_status')
                                    ->label('Payment Status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'succeeded' => 'Succeeded',
                                    ])
                                    ->required(),

                                Forms\Components\TextInput::make('payment_type')
                                    ->label('Payment Method')
                                    ->required(),

                                Forms\Components\TextInput::make('total_tickets')
                                    ->label('Total Tickets')
                                    ->numeric()
                                    ->required(),
                            ])
                    ])
            ]);
    }

    // Tabel untuk menampilkan data
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Kolom order_id
                TextColumn::make('order_id')
                    ->label('Transaction Code')
                    ->sortable()
                    ->searchable(), // Menambahkan kemampuan pencarian

                // Kolom name
                TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(), // Menambahkan kemampuan pencarian

                // Kolom destination name
                TextColumn::make('destination.name')
                    ->label('Destination')
                    ->sortable()
                    ->searchable(), // Menambahkan kemampuan pencarian

                // Kolom ticket_status dengan BadgeColumn
                BadgeColumn::make('ticket_status')
                    ->label('Ticket Status')
                    ->colors([
                        'unused' => 'success',
                        'used' => 'danger',
                    ])
                    ->getStateUsing(fn($record) => $record->ticket_status),

                // Kolom amount
                TextColumn::make('amount')
                    ->label('Amount')
                    ->sortable(),

                // Kolom payment_status dengan BadgeColumn
                BadgeColumn::make('payment_status')
                    ->label('Payment Status')
                    ->colors([
                        'pending' => 'warning',
                        'succeeded' => 'success',
                    ])
                    ->getStateUsing(fn($record) => $record->payment_status),

                // Kolom payment_type
                TextColumn::make('payment_type')
                    ->label('Payment Method')
                    ->sortable(),

                // Kolom total_tickets
                TextColumn::make('total_tickets')
                    ->label('Total Tickets')
                    ->sortable(),
            ])
            ->filters([
                // Filter berdasarkan status pembayaran
                SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'succeeded' => 'Succeeded',
                    ]),
            ])
            ->actions([
                // Aksi untuk melihat detail
                ViewAction::make(),

                // Aksi untuk menghapus
                DeleteAction::make(),
                // Tombol untuk mengubah status tiket menjadi 'used'
                Tables\Actions\Action::make('Approve')
                    ->action(function ($record) {
                        // Ubah status tiket menjadi 'used'
                        $record->update(['ticket_status' => 'used']);
                    })
                    ->requiresConfirmation() // Memastikan pengguna mengonfirmasi aksi ini
                    ->icon('heroicon-o-check') // Menambahkan ikon centang
                    ->color('success') // Warna tombol hijau
                    ->visible(fn($record) => $record->ticket_status === 'unused'), // Hanya muncul jika status tiket 'unused'
            ])
            ->defaultSort('created_at', 'desc')
            ->searchable(); // Menambahkan fitur pencarian untuk seluruh tabel
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
            'index' => Pages\ListTicketTransactions::route('/'),
            'create' => Pages\CreateTicketTransaction::route('/create'),
            // 'edit' => Pages\EditTicketTransaction::route('/{record}/edit'),
        ];
    }
}
