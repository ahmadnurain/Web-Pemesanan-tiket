<?php

namespace App\Filament\Resources\PadDashboardResource\Pages;

use Filament\Forms;
use Filament\Forms\Form; // Pastikan 'Form' di-import
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms; // 1. WAJIB ADA
use Filament\Forms\Concerns\InteractsWithForms; // 2. WAJIB ADA
use App\Models\Destinations;
use Illuminate\Support\Facades\Auth;

use App\Filament\Resources\PadDashboardResource\Widgets\PadAnomalyTable;
use App\Filament\Resources\PadDashboardResource\Widgets\PadStatsOverview;
use App\Filament\Resources\PadDashboardResource\Widgets\PadTimeSeriesChart;
use App\Filament\Resources\PadDashboardResource\Widgets\PadDestinationTable;

class PadDashboard extends Page  // 3. WAJIB ADA
{
    use InteractsWithForms; // 4. WAJIB ADA

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    public static function getNavigationLabel(): string
    {
        $user = Auth::user();

        if ($user?->role === 'super_admin') {
            return 'PAD';
        }

        return 'PAD Destinasi';
    }
    protected static ?string $title = 'PAD Dashboard';
    protected static ?string $slug = 'pad';
    protected static ?string $navigationGroup = 'Monitoring';
    protected static string $view = 'filament.resources.pad-dashboard-resource.pages.pad-dashboard';

    public ?array $filters = [];

    protected $queryString = [];

    protected $casts = [
        'filters' => 'array',
    ];

    public static function canAccess(): bool
    {
        $u = Auth::user();
        return $u && in_array($u->role, ['super_admin', 'admin'], true);
    }

    public function mount(): void
    {
        $this->filters = [
            'from' => now('Asia/Jakarta')->startOfMonth()->toDateString(),
            'to' => now('Asia/Jakarta')->endOfDay()->toDateString(),
            'destination_ids' => [],
        ];

        $this->dispatch('updateFilters', filters: $this->filters);
    }

    public function getWidgets(): array
    {
        return [
            PadStatsOverview::class,
            PadDestinationTable::class,
            PadAnomalyTable::class,
            PadTimeSeriesChart::class,
        ];
    }

    public function getColumns(): int|array
    {
        return [
            'sm' => 1,
            'xl' => 12,
        ];
    }

    public function form(Form $form): Form
    {
        $user = Auth::user();

        $destOptions = Destinations::query()
            ->when($user?->role === 'admin', fn($q) => $q->where('user_id', $user->id))
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();

        return $form
            ->schema([
                Forms\Components\Grid::make(12)->schema([
                    Forms\Components\DatePicker::make('from')
                        ->label('Dari Tanggal')
                        ->native(false)
                        ->displayFormat('d M Y')
                        ->closeOnDateSelection()
                        ->columnSpan(4),

                    Forms\Components\DatePicker::make('to')
                        ->label('Sampai Tanggal')
                        ->native(false)
                        ->displayFormat('d M Y')
                        ->closeOnDateSelection()
                        ->columnSpan(4),

                    Forms\Components\Select::make('destination_ids')
                        ->label('Destinasi')
                        ->multiple()
                        ->options($destOptions)
                        ->searchable()
                        ->preload()
                        ->columnSpan(4),



                ]),

                Forms\Components\Actions::make([
                    Forms\Components\Actions\Action::make('apply_filters')
                        ->label('Terapkan')
                        ->icon('heroicon-o-check')
                        ->extraAttributes(['class' => 'w-full justify-center']) // full width
                        ->action(function () {
                            $this->filters = $this->sanitizeFilters($this->form->getState());
                            $this->dispatch('updateFilters', filters: $this->filters);
                        }),
                ])

                    ->columnSpanFull() // ⬅️ baris baru, penuh
                    ->extraAttributes(['class' => 'mt-2']),

            ])
            ->statePath('filters');
    }

    private function dispatchFiltersUpdate(): void
    {
        $payload = $this->sanitizeFilters($this->filters ?? []);
        $this->dispatch('updateFilters', filters: $payload);
    }

    private function sanitizeFilters(array $in): array
    {
        $out = [];
        foreach ($in as $k => $v) {
            if (is_string($v)) {
                $out[$k] = trim($v);
            } else {
                $out[$k] = $v;
            }
        }
        return $out;
    }

    protected function rules(): array
    {
        return [
            'filters.from' => ['nullable', 'date'],
            'filters.to' => ['nullable', 'date'],
            'filters.destination_ids' => ['array'],
            'filters.destination_ids.*' => ['integer'],
        ];
    }
}
