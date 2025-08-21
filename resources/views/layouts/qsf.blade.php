<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.head') {{-- this will pull in your head.blade.php --}}
</head>
<body class="antialiased bg-gray-100 h-screen flex flex-col">

    {{-- Header --}}
    @include('partials.header') {{-- contains your header code with height 10vh --}}

    {{-- Main Content --}}
    <main class="flex-1 overflow-hidden p-2 bg-[#cbdce8]">
        @yield('content')
    </main>


    {{-- Footer --}}
    @include('partials.footer') {{-- contains your footer code with height 10vh --}}
    @livewireScripts
    @fluxScripts
</body>


</html>
