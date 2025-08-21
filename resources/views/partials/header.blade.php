<header class="shadow p-4 header-bg flex items-center justify-between" style="height: 8vh;">
    <!-- Left: Title -->
    <h1 class="text-xl font-semibold text-white">
        {{ $title ?? 'DSWD System' }}
    </h1>

    <!-- Center: Clock -->
    <span class="text-sm text-gray-200">
        <livewire:clock />
    </span>

    <!-- Right: User Menu -->
    <flux:dropdown position="bottom" align="end">
        <!-- 👇 This is the clickable trigger -->
        <flux:profile
            :name="auth()->user()->name"
            :initials="auth()->user()->initials()"
            icon-trailing="chevron-down"
        />

        <!-- 👇 The dropdown content -->
        <flux:menu class="w-[200px]">
    <flux:menu.radio.group>
        <div class="p-0 text-sm font-normal">
            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                <span class="relative flex h-8 w-full items-center bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white px-2">
                    {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                </span>
            </div>
        </div>
    </flux:menu.radio.group>

    <flux:menu.separator />

    <form method="POST" action="{{ route('logout') }}" class="w-full">
        @csrf
        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
            {{ __('Log Out') }}
        </flux:menu.item>
    </form>
</flux:menu>

    </flux:dropdown>
</header>
