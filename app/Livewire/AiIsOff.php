<?php

namespace App\Livewire;

use Livewire\Component;

class AiIsOff extends Component
{
    public $isOpen = false;
    public function mount()
    {
        // Open modal if session exists
        if (session()->has('ai_is_off')) {
            $this->isOpen = true;
        }
    }

    public function closeModal()
    {
        $this->isOpen = false;
        session()->forget('ai_is_off');
    }
    public function render()
    {
        return view('livewire.ai-is-off');
    }
}
