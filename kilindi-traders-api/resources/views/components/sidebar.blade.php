<div class="flex flex-col h-screen bg-gradient-to-b from-gray-800 to-gray-900 border-r border-gray-700 transition-all duration-300 w-64 shadow-xl" id="sidebar">
    <!-- Header -->
    <div class="flex items-center justify-between p-4 border-b border-gray-700 bg-gradient-to-r from-blue-600 to-blue-700">
        <div class="flex items-center gap-2 sidebar-header-content">
            <i data-lucide="building-2" class="h-6 w-6 text-blue-200"></i>
            <h1 class="font-bold text-white sidebar-text">Kilindi District</h1>
        </div>
        <button onclick="toggleSidebar()" class="text-white hover:bg-blue-500 hover:text-white p-2 rounded flex-shrink-0 transition-colors duration-200">
            <i data-lucide="menu" class="h-4 w-4" id="menu-icon"></i>
            <i data-lucide="chevron-right" class="h-4 w-4 hidden" id="expand-icon"></i>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 p-4 space-y-2">
        @php
            $navigation = [
                ['name' => 'Dashboard', 'href' => route('dashboard'), 'icon' => 'bar-chart-3'],
                ['name' => 'Traders', 'href' => route('traders.index'), 'icon' => 'users'],
                ['name' => 'Debts', 'href' => route('debts.index'), 'icon' => 'credit-card'],
                ['name' => 'Licenses', 'href' => route('licenses.index'), 'icon' => 'file-text'],
                ['name' => 'Payments', 'href' => route('payments.index'), 'icon' => 'wallet'],
                ['name' => 'SMS Logs', 'href' => route('sms.index'), 'icon' => 'message-square'],
                ['name' => 'Import Excel', 'href' => route('imports.index'), 'icon' => 'upload'],
                ['name' => 'Reports', 'href' => route('reports.index'), 'icon' => 'bar-chart-3'],
                ['name' => 'Settings', 'href' => route('settings.index'), 'icon' => 'settings'],
            ];
            $currentRoute = request()->route()->getName();
        @endphp

        @foreach($navigation as $item)
            @php
                $isActive = $currentRoute === str_replace(route('dashboard'), 'dashboard', $item['href']);
                if ($item['href'] === route('dashboard')) $isActive = $currentRoute === 'dashboard';
                if ($item['href'] === route('traders.index')) $isActive = str_starts_with($currentRoute, 'traders.');
                if ($item['href'] === route('debts.index')) $isActive = str_starts_with($currentRoute, 'debts.');
                if ($item['href'] === route('licenses.index')) $isActive = str_starts_with($currentRoute, 'licenses.');
                if ($item['href'] === route('payments.index')) $isActive = str_starts_with($currentRoute, 'payments.');
                if ($item['href'] === route('sms.index')) $isActive = str_starts_with($currentRoute, 'sms.');
                if ($item['href'] === route('imports.index')) $isActive = str_starts_with($currentRoute, 'imports.');
                if ($item['href'] === route('reports.index')) $isActive = str_starts_with($currentRoute, 'reports.');
                if ($item['href'] === route('settings.index')) $isActive = str_starts_with($currentRoute, 'settings.');
            @endphp
            
            <a href="{{ $item['href'] }}" class="block">
                <button class="w-full justify-start gap-3 text-gray-300 flex items-center p-3 rounded-lg transition-all duration-200
                    {{ $isActive ? 'bg-blue-600 text-white shadow-md border-l-4 border-blue-400' : 'hover:bg-gray-700 hover:text-white hover:shadow-md hover:translate-x-1' }}">
                    <i data-lucide="{{ $item['icon'] }}" class="h-4 w-4 flex-shrink-0"></i>
                    <span class="sidebar-text">{{ $item['name'] }}</span>
                </button>
            </a>
        @endforeach
    </nav>

    <!-- Footer -->
    <div class="p-4 border-t border-gray-700 sidebar-footer-content bg-gradient-to-r from-gray-700 to-gray-800" id="sidebar-footer">
        <div class="flex items-center gap-2 text-gray-300">
            <i data-lucide="user" class="h-4 w-4 text-blue-400"></i>
            <span class="text-sm sidebar-text">Admin Panel</span>
        </div>
    </div>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const menuIcon = document.getElementById('menu-icon');
        const expandIcon = document.getElementById('expand-icon');
        const sidebarTexts = document.querySelectorAll('.sidebar-text');
        const sidebarFooter = document.querySelector('.sidebar-footer');
        const headerContent = document.querySelector('.sidebar-header-content');
        
        if (sidebar.classList.contains('w-64')) {
            // Collapse sidebar
            sidebar.classList.remove('w-64');
            sidebar.classList.add('w-16');
            menuIcon.classList.add('hidden');
            expandIcon.classList.remove('hidden');
            sidebarTexts.forEach(text => text.classList.add('hidden'));
            sidebarFooter.classList.add('hidden');
            headerContent.classList.add('hidden');
        } else {
            // Expand sidebar
            sidebar.classList.remove('w-16');
            sidebar.classList.add('w-64');
            menuIcon.classList.remove('hidden');
            expandIcon.classList.add('hidden');
            sidebarTexts.forEach(text => text.classList.remove('hidden'));
            sidebarFooter.classList.remove('hidden');
            headerContent.classList.remove('hidden');
        }
    }
</script>
