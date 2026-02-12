@extends('layouts.admin')
@section('title', 'Admin')
@section('header')
@endsection

@section('content')
    <div class="w-full p-4 bg-gray-200">

        <div class="p-4 sm:ml-64">
            {{-- Dashboard cards --}}
            <div class="grid grid-cols-4 gap-4 mb-6">

                {{-- Waiting Clients --}}
                <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-white shadow">
                    <p class="text-lg font-semibold text-gray-600">Waiting Clients</p>
                    <p class="text-2xl font-bold text-blue-600 waitingCount">{{ $waitingCount }}</p>
                </div>

                {{-- Pending Clients --}}
                <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-white shadow">
                    <p class="text-lg font-semibold text-gray-600">Pending Clients</p>
                    <p class="text-2xl font-bold text-yellow-600 pendingCount">{{ $pendingCount }}</p>
                </div>

                {{-- Serving Clients --}}
                <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-white shadow">
                    <p class="text-lg font-semibold text-gray-600">Serving Clients</p>
                    <p class="text-2xl font-bold text-green-600 servingCount">{{ $servingCount }}</p>
                </div>

                {{-- Priority Clients (visible only for Crisis Intervention Section) --}}
                @php
                    use App\Libraries\Sections;
                @endphp

                {{-- @auth
                    @if (Auth::user()->section_id == Sections::CRISIS_INTERVENTION_SECTION()) --}}
                <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-white shadow">
                    <p class="text-lg font-semibold text-gray-600">Priority Clients</p>
                    <p class="text-2xl font-bold text-red-600 priorityCount">{{ $priorityCount }}</p>
                </div>
                {{-- @endif
                @endauth --}}

                {{-- Regular Clients --}}
                <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-white shadow">
                    <p class="text-lg font-semibold text-gray-600">Regular Clients</p>
                    <p class="text-2xl font-bold text-gray-800 regularCount">{{ $regularCount }}</p>
                </div>

                {{-- Returnee Clients --}}
                <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-white shadow">
                    <p class="text-lg font-semibold text-gray-600">Returnee Clients</p>
                    <p class="text-2xl font-bold text-gray-800 returneeCount">{{ $returneeCount }}</p>
                </div>

                {{-- Completed Clients --}}
                <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-white shadow">
                    <p class="text-lg font-semibold text-gray-600">Completed Clients</p>
                    <p class="text-2xl font-bold text-gray-800 completedCount">{{ $completedCount }}</p>
                </div>
            </div>

            <div id="transactionsTableContainer">
                @include('admin.transactions.table')
            </div>

        </div>
    </div>
@endsection
