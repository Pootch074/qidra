@extends('layouts.qsf')

@section('content')
    <!-- Container = 84vh -->
    <div class="grid grid-rows-[6.5%_90%] h-[84vh] gap-2">
        <!-- Row 1 (10%) -->
        <div class="grid grid-cols-[33%_33%_33%] gap-2 text-2xl font-bold">
            <div class="bg-[#150e60] rounded-lg shadow flex items-center justify-center">WAITING</div>
            <div class="bg-[#150e60] rounded-lg shadow flex items-center justify-center">PENDING</div>
            <div class="bg-[#150e60] rounded-lg shadow flex items-center justify-center">SERVING STEP {{ auth()->user()->step_id }} WINDOW {{ auth()->user()->window_id }}</div>
        </div>


        <!-- Row 2 (90%) -->
        <div class="grid grid-cols-[33%_33%_33%] gap-2">
            <div class="bg-[#f6f9ff] rounded-lg shadow flex items-center justify-center text-black">
                Column 1
            </div>

            <div class="bg-[#f6f9ff] rounded-lg shadow flex items-center justify-center text-black">
                Column 2
            </div>

            <div class="bg-[#f6f9ff] rounded-lg shadow relative text-black">
                <div class="absolute top-0 left-0 w-full h-2/3 flex flex-col items-center">
                    <!-- Top Image -->
                    <img src="{{ Vite::asset('resources/images/col3row1.png') }}" 
                        alt="Top Image" class="object-contain">

                    <!-- Text (closer to the Top Image) -->
                    <div class="mt-2 text-center font-bold text-xl">
                        {{ auth()->user()->field_office }} <br>
                        {{ auth()->user()->section->division->division_name ?? '' }} <br>
                        {{ auth()->user()->section->section_name ?? '' }}
                    </div>

                    <!-- Bottom Image -->
                    <img src="{{ Vite::asset('resources/images/col3row3.png') }}" 
                        alt="Bottom Image" class="object-contain mt-auto">
                </div>

                <div class="absolute bottom-0 left-0 w-full h-1/3 bg-blue-200 flex items-center justify-center">
                    Card 2
                </div>
            </div>


        </div>


    </div>
@endsection
