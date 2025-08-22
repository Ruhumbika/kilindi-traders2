@extends('layouts.app')

@section('title', ucfirst($type) . ' Report')

@section('content')
<header class="border-b border-border bg-card px-6 py-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-card-foreground">{{ ucfirst($type) }} Report</h1>
            <p class="text-muted-foreground">{{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <i data-lucide="printer" class="h-4 w-4"></i>
                Print
            </button>
            <a href="{{ route('reports.index') }}" class="text-muted-foreground hover:text-foreground flex items-center gap-2">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Back
            </a>
        </div>
    </div>
</header>

<main class="flex-1 overflow-auto p-6">
    <div class="max-w-7xl mx-auto space-y-6">
        @if($type === 'financial')
            <!-- Financial Report -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-card border border-border rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-muted-foreground">Total Revenue</h3>
                    <p class="text-2xl font-bold text-green-600">TSh {{ number_format($reportData['totalRevenue']) }}</p>
                </div>
                <div class="bg-card border border-border rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-muted-foreground">Debt Payments</h3>
                    <p class="text-2xl font-bold text-blue-600">TSh {{ number_format($reportData['debtPayments']) }}</p>
                </div>
                <div class="bg-card border border-border rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-muted-foreground">License Payments</h3>
                    <p class="text-2xl font-bold text-purple-600">TSh {{ number_format($reportData['licensePayments']) }}</p>
                </div>
                <div class="bg-card border border-border rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-muted-foreground">Outstanding Debts</h3>
                    <p class="text-2xl font-bold text-red-600">TSh {{ number_format($reportData['outstandingDebts']) }}</p>
                </div>
            </div>

            <!-- Payment Methods Breakdown -->
            <div class="bg-card border border-border rounded-lg shadow-sm">
                <div class="p-6">
                    <h3 class="font-semibold mb-4">Payment Methods Breakdown</h3>
                    <div class="space-y-3">
                        @foreach($reportData['paymentMethods'] as $method => $amount)
                            <div class="flex justify-between items-center">
                                <span class="capitalize">{{ str_replace('_', ' ', $method) }}</span>
                                <span class="font-mono font-medium">TSh {{ number_format($amount) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        @elseif($type === 'traders')
            <!-- Traders Report -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-card border border-border rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-muted-foreground">Total Traders</h3>
                    <p class="text-2xl font-bold">{{ $reportData['totalTraders'] }}</p>
                </div>
                <div class="bg-card border border-border rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-muted-foreground">New Traders</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $reportData['newTraders'] }}</p>
                </div>
                <div class="bg-card border border-border rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-muted-foreground">With Debts</h3>
                    <p class="text-2xl font-bold text-red-600">{{ $reportData['tradersWithDebts'] }}</p>
                </div>
                <div class="bg-card border border-border rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-muted-foreground">With Licenses</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $reportData['tradersWithLicenses'] }}</p>
                </div>
            </div>

            <!-- Business Type Breakdown -->
            <div class="bg-card border border-border rounded-lg shadow-sm">
                <div class="p-6">
                    <h3 class="font-semibold mb-4">Business Type Distribution</h3>
                    <div class="space-y-3">
                        @foreach($reportData['businessTypeBreakdown'] as $type => $count)
                            <div class="flex justify-between items-center">
                                <span class="capitalize">{{ $type ?: 'Not specified' }}</span>
                                <span class="font-medium">{{ $count }} traders</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        @elseif($type === 'debts')
            <!-- Debts Report -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-card border border-border rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-muted-foreground">Total Amount</h3>
                    <p class="text-2xl font-bold">TSh {{ number_format($reportData['totalAmount']) }}</p>
                </div>
                <div class="bg-card border border-border rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-muted-foreground">Paid Amount</h3>
                    <p class="text-2xl font-bold text-green-600">TSh {{ number_format($reportData['paidAmount']) }}</p>
                </div>
                <div class="bg-card border border-border rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-muted-foreground">Pending Amount</h3>
                    <p class="text-2xl font-bold text-yellow-600">TSh {{ number_format($reportData['pendingAmount']) }}</p>
                </div>
                <div class="bg-card border border-border rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-muted-foreground">Collection Rate</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $reportData['collectionRate'] }}%</p>
                </div>
            </div>

        @elseif($type === 'licenses')
            <!-- Licenses Report -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-card border border-border rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-muted-foreground">Total Licenses</h3>
                    <p class="text-2xl font-bold">{{ $reportData['totalLicenses'] }}</p>
                </div>
                <div class="bg-card border border-border rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-muted-foreground">New Licenses</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $reportData['newLicenses'] }}</p>
                </div>
                <div class="bg-card border border-border rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-muted-foreground">Expired</h3>
                    <p class="text-2xl font-bold text-red-600">{{ $reportData['expiredLicenses'] }}</p>
                </div>
                <div class="bg-card border border-border rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-muted-foreground">Total Revenue</h3>
                    <p class="text-2xl font-bold text-purple-600">TSh {{ number_format($reportData['totalRevenue']) }}</p>
                </div>
            </div>

        @elseif($type === 'sms')
            <!-- SMS Report -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-card border border-border rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-muted-foreground">Total SMS</h3>
                    <p class="text-2xl font-bold">{{ $reportData['totalSms'] }}</p>
                </div>
                <div class="bg-card border border-border rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-muted-foreground">Sent SMS</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $reportData['sentSms'] }}</p>
                </div>
                <div class="bg-card border border-border rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-muted-foreground">Success Rate</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $reportData['successRate'] }}%</p>
                </div>
                <div class="bg-card border border-border rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-muted-foreground">Estimated Cost</h3>
                    <p class="text-2xl font-bold text-orange-600">TSh {{ number_format($reportData['estimatedCost']) }}</p>
                </div>
            </div>

        @elseif($type === 'summary')
            <!-- Summary Report -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="bg-card border border-border rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-muted-foreground">Revenue</h3>
                    <p class="text-2xl font-bold text-green-600">TSh {{ number_format($reportData['financial']['totalRevenue']) }}</p>
                    <p class="text-xs text-muted-foreground">{{ $reportData['financial']['totalPayments'] }} payments</p>
                </div>
                <div class="bg-card border border-border rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-muted-foreground">Traders</h3>
                    <p class="text-2xl font-bold">{{ $reportData['traders']['totalTraders'] }}</p>
                    <p class="text-xs text-muted-foreground">+{{ $reportData['traders']['newTraders'] }} new</p>
                </div>
                <div class="bg-card border border-border rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-muted-foreground">Debts</h3>
                    <p class="text-2xl font-bold text-red-600">TSh {{ number_format($reportData['debts']['totalAmount']) }}</p>
                    <p class="text-xs text-muted-foreground">{{ $reportData['debts']['collectionRate'] }}% collected</p>
                </div>
                <div class="bg-card border border-border rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-muted-foreground">Licenses</h3>
                    <p class="text-2xl font-bold">{{ $reportData['licenses']['totalLicenses'] }}</p>
                    <p class="text-xs text-muted-foreground">{{ $reportData['licenses']['expiredLicenses'] }} expired</p>
                </div>
                <div class="bg-card border border-border rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-muted-foreground">SMS</h3>
                    <p class="text-2xl font-bold">{{ $reportData['sms']['totalSms'] }}</p>
                    <p class="text-xs text-muted-foreground">{{ $reportData['sms']['successRate'] }}% success</p>
                </div>
            </div>
        @endif

        <!-- Export Options -->
        <div class="bg-card border border-border rounded-lg shadow-sm">
            <div class="p-6">
                <h3 class="font-semibold mb-4">Export Options</h3>
                <div class="flex items-center gap-3">
                    <button onclick="exportToPDF()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                        <i data-lucide="file-text" class="h-4 w-4"></i>
                        Export PDF
                    </button>
                    <button onclick="exportToExcel()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                        <i data-lucide="table" class="h-4 w-4"></i>
                        Export Excel
                    </button>
                    <button onclick="emailReport()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                        <i data-lucide="mail" class="h-4 w-4"></i>
                        Email Report
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    function exportToPDF() {
        alert('PDF export functionality would be implemented here');
    }

    function exportToExcel() {
        alert('Excel export functionality would be implemented here');
    }

    function emailReport() {
        alert('Email report functionality would be implemented here');
    }
</script>

<style>
    @media print {
        .no-print {
            display: none !important;
        }
        
        body {
            background: white !important;
        }
        
        .bg-card {
            background: white !important;
            border: 1px solid #ccc !important;
        }
    }
</style>
@endsection
