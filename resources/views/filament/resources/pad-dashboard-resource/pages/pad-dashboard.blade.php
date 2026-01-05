<x-filament-panels::page>

    {{-- Form ini akan ditampilkan secara otomatis oleh HasForms --}}
    {{ $this->form }}

    {{-- Ini adalah widget Anda --}}
    <x-filament-widgets::widgets
        :widgets="$this->getWidgets()"
/>

</x-filament-panels::page>
