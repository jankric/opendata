<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg p-6 text-white">
            <h2 class="text-2xl font-bold mb-2">Selamat Datang di Admin Dashboard</h2>
            <p class="text-blue-100">Portal Data Terbuka Kabupaten Gorontalo</p>
        </div>

        <!-- Widgets -->
        <x-filament-widgets::widgets
            :widgets="$this->getWidgets()"
            :columns="$this->getColumns()"
        />
    </div>
</x-filament-panels::page>