@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<header class="border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 shadow-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Dashboard</h1>
            <p class="text-blue-100">Kilindi District Traders Management System</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2 text-sm text-blue-100">
                <i data-lucide="calendar" class="h-4 w-4"></i>
                {{ now()->format('l, F j, Y') }}
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm text-white font-medium">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-sm flex items-center gap-1 shadow-md transition-all duration-200 hover:shadow-lg">
                        <i data-lucide="log-out" class="h-4 w-4"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<main class="flex-1 overflow-auto p-6 bg-gray-50">
    <div class="max-w-7xl mx-auto space-y-6 overflow-x-auto">
        <!-- Main Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                    <h3 class="text-sm font-medium text-gray-600">Total Traders</h3>
                    <i data-lucide="users" class="h-4 w-4 text-blue-500"></i>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['total_traders'] }}</div>
                    <p class="text-xs text-gray-500">Registered businesses</p>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                    <h3 class="text-sm font-medium text-gray-600">Total Revenue</h3>
                    <i data-lucide="trending-up" class="h-4 w-4 text-green-500"></i>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-2xl font-bold text-green-600">TSh {{ number_format($stats['total_payment_amount']) }}</div>
                    <p class="text-xs text-gray-500">From payments received</p>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                    <h3 class="text-sm font-medium text-gray-600">Pending Debts</h3>
                    <i data-lucide="credit-card" class="h-4 w-4 text-orange-500"></i>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-2xl font-bold text-orange-600">TSh {{ number_format($stats['pending_debt_amount']) }}</div>
                    <p class="text-xs text-gray-500">Outstanding payments</p>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                    <h3 class="text-sm font-medium text-gray-600">Expiring Licenses</h3>
                    <i data-lucide="alert-triangle" class="h-4 w-4 text-amber-500"></i>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-2xl font-bold text-amber-600">{{ $stats['expired_licenses'] }}</div>
                    <p class="text-xs text-gray-500">Require renewal soon</p>
                </div>
            </div>
        </div>

        <!-- Debt Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                    <h3 class="text-sm font-medium text-gray-600">Paid Debts</h3>
                    <i data-lucide="check-circle" class="h-4 w-4 text-green-500"></i>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-2xl font-bold text-green-600">TSh {{ number_format($stats['paid_debt_amount']) }}</div>
                    <p class="text-xs text-gray-500">Successfully collected</p>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                    <h3 class="text-sm font-medium text-gray-600">Overdue Debts</h3>
                    <i data-lucide="alert-triangle" class="h-4 w-4 text-red-500"></i>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-2xl font-bold text-red-600">TSh {{ number_format($stats['overdue_debt_amount']) }}</div>
                    <p class="text-xs text-gray-500">Past due date</p>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                <div class="flex flex-row items-center justify-between space-y-0 p-6 pb-2">
                    <h3 class="text-sm font-medium text-gray-600">Total Debts</h3>
                    <i data-lucide="file-text" class="h-4 w-4 text-purple-500"></i>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-2xl font-bold text-purple-600">{{ $stats['total_debts'] }}</div>
                    <p class="text-xs text-gray-500">Debt records</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <h3 class="font-semibold text-gray-800">Quick Actions</h3>
                <p class="text-sm text-gray-600">Common administrative tasks</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('traders.index') }}" class="flex items-center gap-3 p-4 rounded-lg border border-gray-200 hover:bg-blue-50 hover:border-blue-300 transition-all duration-200 hover:shadow-md">
                        <i data-lucide="users" class="h-5 w-5 text-blue-500"></i>
                        <div>
                            <p class="font-medium text-gray-800">Manage Traders</p>
                            <p class="text-sm text-gray-600">Add or edit trader records</p>
                        </div>
                    </a>

                    <a href="{{ route('debts.index') }}" class="flex items-center gap-3 p-4 rounded-lg border border-gray-200 hover:bg-green-50 hover:border-green-300 transition-all duration-200 hover:shadow-md">
                        <i data-lucide="credit-card" class="h-5 w-5 text-green-500"></i>
                        <div>
                            <p class="font-medium text-gray-800">Debt Collection</p>
                            <p class="text-sm text-gray-600">Track and collect debts</p>
                        </div>
                    </a>

                    <a href="{{ route('licenses.index') }}" class="flex items-center gap-3 p-4 rounded-lg border border-gray-200 hover:bg-purple-50 hover:border-purple-300 transition-all duration-200 hover:shadow-md">
                        <i data-lucide="file-text" class="h-5 w-5 text-purple-500"></i>
                        <div>
                            <p class="font-medium text-gray-800">License Management</p>
                            <p class="text-sm text-gray-600">Renew and track licenses</p>
                        </div>
                    </a>

                    <a href="#" class="flex items-center gap-3 p-4 rounded-lg border border-gray-200 hover:bg-orange-50 hover:border-orange-300 transition-all duration-200 hover:shadow-md">
                        <i data-lucide="trending-up" class="h-5 w-5 text-orange-500"></i>
                        <div>
                            <p class="font-medium text-gray-800">Generate Reports</p>
                            <p class="text-sm text-gray-600">Financial and activity reports</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- System Overview -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <h3 class="font-semibold text-gray-800">System Overview</h3>
                <p class="text-sm text-gray-600">Key metrics and system health</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        @php
                            $totalDebt = $stats['paid_debt_amount'] + $stats['pending_debt_amount'] + $stats['overdue_debt_amount'];
                            $collectionRate = $totalDebt > 0 ? round(($stats['paid_debt_amount'] / $totalDebt) * 100) : 0;
                        @endphp
                        <div class="text-3xl font-bold text-blue-600 mb-2">{{ $collectionRate }}%</div>
                        <p class="text-sm text-gray-600">Debt Collection Rate</p>
                    </div>

                    <div class="text-center">
                        @php
                            $avgRevenue = $stats['total_traders'] > 0 ? $stats['total_revenue'] / $stats['total_traders'] : 0;
                        @endphp
                        <div class="text-3xl font-bold text-green-600 mb-2">TSh {{ number_format($avgRevenue) }}</div>
                        <p class="text-sm text-gray-600">Average Revenue per Trader</p>
                    </div>

                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600 mb-2">{{ $stats['expiring_licenses'] }}</div>
                        <p class="text-sm text-gray-600">Licenses Need Attention</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
