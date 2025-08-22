@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="text-center py-12">
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-md mx-auto">
        <i class="fas fa-tools text-6xl text-gray-400 mb-4"></i>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $title }}</h2>
        <p class="text-gray-600 mb-6">This section is coming soon. The API endpoints are ready!</p>
        <a href="{{ route('dashboard') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
            <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
        </a>
    </div>
</div>
@endsection
