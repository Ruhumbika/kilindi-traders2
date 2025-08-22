@extends('layouts.app')

@section('title', 'Reports & Analytics')

@section('content')
<header class="border-b border-border bg-card px-6 py-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-card-foreground">Reports & Analytics</h1>
            <p class="text-muted-foreground">Generate comprehensive business reports</p>
        </div>
    </div>
</header>

<main class="flex-1 overflow-auto p-6">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Report Generator -->
        <div class="bg-card border border-border rounded-lg shadow-sm">
            <div class="p-6">
                <h3 class="font-semibold mb-4">Generate Report</h3>
                <form action="{{ route('reports.generate') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="space-y-2">
                            <label for="type" class="text-sm font-medium text-foreground">Report Type *</label>
                            <select id="type" name="type" required
                                    class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select report type</option>
                                <option value="summary">Summary Report</option>
                                <option value="financial">Financial Report</option>
                                <option value="traders">Traders Report</option>
                                <option value="debts">Debts Report</option>
                                <option value="licenses">Licenses Report</option>
                                <option value="sms">SMS Report</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label for="start_date" class="text-sm font-medium text-foreground">Start Date *</label>
                            <input type="date" id="start_date" name="start_date" required
                                   value="{{ date('Y-m-01') }}"
                                   class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div class="space-y-2">
                            <label for="end_date" class="text-sm font-medium text-foreground">End Date *</label>
                            <input type="date" id="end_date" name="end_date" required
                                   value="{{ date('Y-m-d') }}"
                                   class="w-full border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <div class="flex items-center justify-end">
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 rounded-lg flex items-center gap-2">
                            <i data-lucide="bar-chart" class="h-4 w-4"></i>
                            Generate Report
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Quick Reports -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="bg-card border border-border rounded-lg shadow-sm p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i data-lucide="trending-up" class="h-6 w-6 text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold">Financial Summary</h3>
                        <p class="text-sm text-muted-foreground">Revenue and payment analysis</p>
                    </div>
                </div>
                <form action="{{ route('reports.generate') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="type" value="financial">
                    <input type="hidden" name="start_date" value="{{ date('Y-m-01') }}">
                    <input type="hidden" name="end_date" value="{{ date('Y-m-d') }}">
                    <button type="submit" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Generate This Month →
                    </button>
                </form>
            </div>

            <div class="bg-card border border-border rounded-lg shadow-sm p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <i data-lucide="users" class="h-6 w-6 text-green-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold">Traders Analysis</h3>
                        <p class="text-sm text-muted-foreground">Registration and activity trends</p>
                    </div>
                </div>
                <form action="{{ route('reports.generate') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="type" value="traders">
                    <input type="hidden" name="start_date" value="{{ date('Y-01-01') }}">
                    <input type="hidden" name="end_date" value="{{ date('Y-m-d') }}">
                    <button type="submit" class="text-green-600 hover:text-green-800 text-sm font-medium">
                        Generate This Year →
                    </button>
                </form>
            </div>

            <div class="bg-card border border-border rounded-lg shadow-sm p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-red-100 rounded-lg">
                        <i data-lucide="credit-card" class="h-6 w-6 text-red-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold">Debt Collection</h3>
                        <p class="text-sm text-muted-foreground">Outstanding and collection rates</p>
                    </div>
                </div>
                <form action="{{ route('reports.generate') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="type" value="debts">
                    <input type="hidden" name="start_date" value="{{ date('Y-m-01') }}">
                    <input type="hidden" name="end_date" value="{{ date('Y-m-d') }}">
                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                        Generate This Month →
                    </button>
                </form>
            </div>

            <div class="bg-card border border-border rounded-lg shadow-sm p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <i data-lucide="file-text" class="h-6 w-6 text-purple-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold">License Compliance</h3>
                        <p class="text-sm text-muted-foreground">License status and renewals</p>
                    </div>
                </div>
                <form action="{{ route('reports.generate') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="type" value="licenses">
                    <input type="hidden" name="start_date" value="{{ date('Y-01-01') }}">
                    <input type="hidden" name="end_date" value="{{ date('Y-m-d') }}">
                    <button type="submit" class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                        Generate This Year →
                    </button>
                </form>
            </div>

            <div class="bg-card border border-border rounded-lg shadow-sm p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-orange-100 rounded-lg">
                        <i data-lucide="message-circle" class="h-6 w-6 text-orange-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold">SMS Analytics</h3>
                        <p class="text-sm text-muted-foreground">Communication effectiveness</p>
                    </div>
                </div>
                <form action="{{ route('reports.generate') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="type" value="sms">
                    <input type="hidden" name="start_date" value="{{ date('Y-m-01') }}">
                    <input type="hidden" name="end_date" value="{{ date('Y-m-d') }}">
                    <button type="submit" class="text-orange-600 hover:text-orange-800 text-sm font-medium">
                        Generate This Month →
                    </button>
                </form>
            </div>

            <div class="bg-card border border-border rounded-lg shadow-sm p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-gray-100 rounded-lg">
                        <i data-lucide="pie-chart" class="h-6 w-6 text-gray-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold">Complete Summary</h3>
                        <p class="text-sm text-muted-foreground">All-in-one overview report</p>
                    </div>
                </div>
                <form action="{{ route('reports.generate') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="type" value="summary">
                    <input type="hidden" name="start_date" value="{{ date('Y-m-01') }}">
                    <input type="hidden" name="end_date" value="{{ date('Y-m-d') }}">
                    <button type="submit" class="text-gray-600 hover:text-gray-800 text-sm font-medium">
                        Generate This Month →
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
