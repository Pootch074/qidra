@extends('layouts.display')
@section('title', 'Display')
@section('header')


@endsection

@section('content')
    <div class="w-full h-[100vh] flex flex-col md:flex-row">
        <div class="md:w-7/12 w-full bg-[#2e3192] p-3 flex flex-col h-full">
            <div id="stepsContainer" class="flex flex-col w-full h-full justify-start">
            </div>
            <div id="noSteps" class="hidden text-white text-lg font-medium">
                No steps available for your section.
            </div>
        </div>

        <div class="md:w-5/12 w-full bg-gray-800 text-white flex flex-col justify-start h-full">
            <div class="w-full h-[8vh] bg-[#2e3192]">
                <div x-data="{ open: false }" class="flex items-center justify-between w-full px-4">
                    <img class="h-15 w-auto mt-3" src="{{ Vite::asset('resources/images/dswd-white.png') }}" alt="Logo">
                    <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                        @if (auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" alt="Profile" class="w-8 h-8 rounded-full mr-5">
                        @else
                        @endif

                        <div class="hidden sm:block mr-5 text-left">
                            <span class="block text-white font-semibold text-2xl">
                                {{ Str::upper(optional(auth()->user()->section)->section_name ?? 'NO SECTION') }}
                            </span>

                        </div>

                        <div class="w-5 h-5" aria-hidden="true">
                            <img x-show="!open" x-cloak src="{{ Vite::asset('resources/images/icons/caret-down.png') }}"
                                alt="" class="w-5 h-5 object-contain" />
                            <img x-show="open" x-cloak src="{{ Vite::asset('resources/images/icons/caret-up.png') }}"
                                alt="" class="w-5 h-5 object-contain" />
                        </div>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" x-transition @click.outside="open = false"
                        class="absolute right-0 mt-20 rounded-md shadow-lg">
                        <a href="{{ route('logout') }}"
                            class="text-[#ee1c25] hover:text-white border border-[#ee1c25] hover:bg-[#ee1c25] focus:ring-1 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-[#ee1c25] dark:text-[#ee1c25] dark:hover:text-white dark:hover:bg-[#ee1c25] dark:focus:ring-gray-800">Logout</a>
                    </div>
                </div>
            </div>


            <div class="flex w-full h-[24vh]">
                <div class="flex flex-col justify-center items-center p-4 flex-1 text-center">
                    <p id="current-date" class="text-1xl md:text-2xl font-bold mb-2"></p>
                    <p id="current-time" class="text-2xl md:text-5xl font-bold"></p>
                </div>
                <div class="flex flex-col flex-1 justify-center">
                    <video id="customVideo" class="w-full rounded-lg shadow-lg" autoplay muted loop>
                        <source src="{{ asset('assets/videos/dswd.mp4') }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>


            {{-- Flash new serving queue --}}
            <div id="flashServingQueue"
                class="flex items-center justify-center h-[60vh] bg-gray-900 text-white text-[12rem] font-extrabold rounded-lg relative overflow-hidden">
                <span id="servingNumber" class="hidden"></span>
            </div>



            <div class="w-full flex flex-col items-center space-y-4 bg-red-300 h-[8vh]">
                <div class="w-full h-full bg-[#2e3192] text-white flex items-center overflow-hidden relative">
                    <div class="whitespace-nowrap flex items-center marquee">
                        <span class="mx-8 text-lg md:text-xl font-semibold">
                            Bawat Buhay Mahalaga sa DSWD
                        </span>
                        <span class="mx-8 text-lg md:text-xl font-semibold">
                            Bawat Buhay Mahalaga sa DSWD
                        </span>
                        <span class="mx-8 text-lg md:text-xl font-semibold">
                            Bawat Buhay Mahalaga sa DSWD
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @php
        $appSteps = [
            'PRE_ASSESSMENT' => \App\Libraries\Steps::id('Pre-assessment'),
            'ENCODING' => \App\Libraries\Steps::id('Encoding'),
            'ASSESSMENT' => \App\Libraries\Steps::id('Assessment'),
            'RELEASE' => \App\Libraries\Steps::id('Release'),
        ];

        $appSections = [
            'CRISIS_INTERVENTION_SECTION' => \App\Models\Section::where(
                'section_name',
                'CRISIS INTERVENTION SECTION',
            )->value('id'),
        ];
    @endphp

    <script>
        window.appSteps = @json($appSteps);
        window.appSections = @json($appSections);

        window.appUser = {
            id: {{ Auth::id() }},
            name: @json(Auth::user()->first_name . ' ' . Auth::user()->last_name),
            section_id: {{ Auth::user()->section_id }},
            assignedCategory: @json(Auth::user()->assigned_category ?? ''),
        };

        window.appRoutes = {
            steps: "{{ route('steps') }}",
            latestTransaction: "{{ route('display.latest-transaction') }}"
        };

        window.alertAudioUrl = "{{ asset('audio/alert1.mp3') }}";
    </script>

    <style>
        @keyframes flash {
            0% {
                background-color: #ee1c25;
            }

            /* red */
            25% {
                background-color: #fef200;
            }

            /* yellow */
            50% {
                background-color: #ee1c25;
            }

            /* red */
            75% {
                background-color: #fef200;
            }

            /* yellow */
            100% {
                background-color: #ee1c25;
            }

            /* red */
        }

        .animate-flash {
            animation: flash 1.5s infinite;
        }

        #flashServingQueue {
            transition: background-color 0.3s ease;
        }
    </style>

    @vite('resources/js/display.js')
@endsection
