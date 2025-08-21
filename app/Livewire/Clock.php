<?php

namespace App\Livewire;

use Livewire\Component;

class Clock extends Component
{
    public string $now;

    public function mount(): void
    {
        $this->tick();
    }

    public function tick(): void
    {
        // optional: respect app timezone
        $this->now = now()->timezone(config('app.timezone'))
            ->format('l, d F Y \a\t h:i A');
    }

    public function render()
    {
        return view('livewire.clock');
    }
}
