@extends('layouts.app')

@section('title', 'Import Excel Data')

@section('content')
<div class="flex-1 overflow-auto bg-gray-50">
    <div class="p-6">
        <div class="max-w-6xl mx-auto">
            <!-- Header Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2 flex items-center">
                            <i data-lucide="upload" class="h-8 w-8 text-blue-600 mr-3"></i>
                            Import Excel Data
                        </h1>
                        <p class="text-gray-600">Streamline data entry by uploading Excel files for traders, debts, and licenses.</p>
                    </div>
                    <div class="hidden md:block">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="text-sm text-blue-800 font-medium">Quick Stats</div>
                            <div class="text-2xl font-bold text-blue-900">1/3</div>
                            <div class="text-xs text-blue-600">Import Types Available</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 rounded-lg">
                    <div class="flex items-center">
                        <i data-lucide="check-circle" class="h-5 w-5 text-green-400 mr-3"></i>
                        <p class="text-green-800 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 rounded-lg">
                    <div class="flex items-center">
                        <i data-lucide="alert-circle" class="h-5 w-5 text-red-400 mr-3"></i>
                        <p class="text-red-800 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 rounded-lg">
                    <div class="flex items-start">
                        <i data-lucide="alert-triangle" class="h-5 w-5 text-red-400 mr-3 mt-0.5"></i>
                        <div>
                            <p class="text-red-800 font-medium mb-2">Please fix the following errors:</p>
                            <ul class="list-disc list-inside text-red-700 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Import Options Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Traders Import Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4">
                        <div class="flex items-center text-white">
                            <i data-lucide="users" class="h-8 w-8 mr-3"></i>
                            <div>
                                <h2 class="text-xl font-semibold">Traders Import</h2>
                                <p class="text-blue-100 text-sm">Upload trader information</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Status</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i data-lucide="check" class="h-3 w-3 mr-1"></i>
                                    Available
                                </span>
                            </div>
                            <p class="text-gray-600 text-sm">Import trader records including names, contacts, and business details.</p>
                        </div>
                        
                        <form action="{{ route('imports.traders') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label for="traders_file" class="block text-sm font-medium text-gray-700 mb-2">
                                    Select Excel File
                                </label>
                                <div class="relative">
                                    <input type="file" 
                                           id="traders_file" 
                                           name="file" 
                                           accept=".xlsx,.xls,.csv"
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           required>
                                </div>
                            </div>
                            
                            <div class="flex space-x-3">
                                <button type="submit" class="flex-1 bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors font-medium flex items-center justify-center">
                                    <i data-lucide="upload" class="h-4 w-4 mr-2"></i>
                                    Import Now
                                </button>
                            </div>
                        </form>
                        
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <a href="{{ route('imports.traders.template') }}" 
                               class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 font-medium">
                                <i data-lucide="download" class="h-4 w-4 mr-2"></i>
                                Download Sample Template (CSV)
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Debts Import Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-500 to-red-500 p-4">
                        <div class="flex items-center text-white">
                            <i data-lucide="credit-card" class="h-8 w-8 mr-3"></i>
                            <div>
                                <h2 class="text-xl font-semibold">Debts Import</h2>
                                <p class="text-orange-100 text-sm">Upload debt records</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Status</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i data-lucide="clock" class="h-3 w-3 mr-1"></i>
                                    Coming Soon
                                </span>
                            </div>
                            <p class="text-gray-600 text-sm">Import debt records with amounts, due dates, and trader associations.</p>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Select Excel File
                                </label>
                                <input type="file" 
                                       accept=".xlsx,.xls,.csv"
                                       class="block w-full text-sm text-gray-400 file:mr-4 file:py-3 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-500 border border-gray-300 rounded-lg cursor-not-allowed"
                                       disabled>
                            </div>
                            
                            <button type="button" class="w-full bg-gray-300 text-gray-500 py-3 px-4 rounded-lg cursor-not-allowed font-medium" disabled>
                                Feature Under Development
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Licenses Import Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-500 to-emerald-500 p-4">
                        <div class="flex items-center text-white">
                            <i data-lucide="file-text" class="h-8 w-8 mr-3"></i>
                            <div>
                                <h2 class="text-xl font-semibold">Licenses Import</h2>
                                <p class="text-green-100 text-sm">Upload license data</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Status</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i data-lucide="clock" class="h-3 w-3 mr-1"></i>
                                    Coming Soon
                                </span>
                            </div>
                            <p class="text-gray-600 text-sm">Import business license information including types, expiry dates, and fees.</p>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Select Excel File
                                </label>
                                <input type="file" 
                                       accept=".xlsx,.xls,.csv"
                                       class="block w-full text-sm text-gray-400 file:mr-4 file:py-3 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-500 border border-gray-300 rounded-lg cursor-not-allowed"
                                       disabled>
                            </div>
                            
                            <button type="button" class="w-full bg-gray-300 text-gray-500 py-3 px-4 rounded-lg cursor-not-allowed font-medium" disabled>
                                Feature Under Development
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Instructions and Guidelines -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-4">
                    <h3 class="text-xl font-semibold text-white flex items-center">
                        <i data-lucide="info" class="h-6 w-6 mr-3"></i>
                        Import Guidelines & Requirements
                    </h3>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <i data-lucide="file-check" class="h-5 w-5 text-green-600 mr-2"></i>
                                File Requirements
                            </h4>
                            <ul class="space-y-2 text-gray-700">
                                <li class="flex items-start">
                                    <i data-lucide="check" class="h-4 w-4 text-green-600 mr-2 mt-0.5 flex-shrink-0"></i>
                                    <span class="text-sm">Supported formats: .xlsx, .xls, .csv</span>
                                </li>
                                <li class="flex items-start">
                                    <i data-lucide="check" class="h-4 w-4 text-green-600 mr-2 mt-0.5 flex-shrink-0"></i>
                                    <span class="text-sm">Maximum file size: 10MB</span>
                                </li>
                                <li class="flex items-start">
                                    <i data-lucide="check" class="h-4 w-4 text-green-600 mr-2 mt-0.5 flex-shrink-0"></i>
                                    <span class="text-sm">First row must contain column headers</span>
                                </li>
                                <li class="flex items-start">
                                    <i data-lucide="check" class="h-4 w-4 text-green-600 mr-2 mt-0.5 flex-shrink-0"></i>
                                    <span class="text-sm">Use provided templates for best results</span>
                                </li>
                            </ul>
                        </div>
                        
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <i data-lucide="shield-check" class="h-5 w-5 text-blue-600 mr-2"></i>
                                Data Validation
                            </h4>
                            <ul class="space-y-2 text-gray-700">
                                <li class="flex items-start">
                                    <i data-lucide="check" class="h-4 w-4 text-blue-600 mr-2 mt-0.5 flex-shrink-0"></i>
                                    <span class="text-sm">All data is validated before import</span>
                                </li>
                                <li class="flex items-start">
                                    <i data-lucide="check" class="h-4 w-4 text-blue-600 mr-2 mt-0.5 flex-shrink-0"></i>
                                    <span class="text-sm">Invalid records are skipped with detailed reports</span>
                                </li>
                                <li class="flex items-start">
                                    <i data-lucide="check" class="h-4 w-4 text-blue-600 mr-2 mt-0.5 flex-shrink-0"></i>
                                    <span class="text-sm">Duplicate entries are automatically detected</span>
                                </li>
                                <li class="flex items-start">
                                    <i data-lucide="check" class="h-4 w-4 text-blue-600 mr-2 mt-0.5 flex-shrink-0"></i>
                                    <span class="text-sm">Supports both English and Swahili headers</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="mt-6 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                        <div class="flex items-start">
                            <i data-lucide="alert-triangle" class="h-5 w-5 text-amber-600 mr-3 mt-0.5 flex-shrink-0"></i>
                            <div>
                                <h5 class="font-medium text-amber-900 mb-1">Important Notes</h5>
                                <p class="text-sm text-amber-800">
                                    Always backup your data before performing bulk imports. Review the sample templates carefully to ensure your data matches the expected format. Large files may take several minutes to process.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script>
    lucide.createIcons();
    
    // Add some interactivity
    document.addEventListener('DOMContentLoaded', function() {
        const fileInputs = document.querySelectorAll('input[type="file"]');
        
        fileInputs.forEach(input => {
            input.addEventListener('change', function() {
                const fileName = this.files[0]?.name;
                if (fileName) {
                    console.log('Selected file:', fileName);
                }
            });
        });
    });
</script>
@endsection
